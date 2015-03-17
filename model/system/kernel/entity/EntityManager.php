<?php
/**
 * Classe respons�vel pelo gerenciamento das entidades (anotations) do ORM.
 * 
 * @author Andre
 */
class EntityManager {
	
	/**
	 * Recupera a lista de todas as classes ORM.
	 * 
	 * @return array Vetor contendo a lista de Objetos para cria��o do BD.
	 */
	public function requestDataTables() {
		
		// Caminho para os modelos do sistema.
		$loaderClass = new LoaderClass();
		$path = Application::getPath() . Application::DIR_MODEL . DIRECTORY_SEPARATOR;
		
		// Armazena as estruturas de classe.
		$classList = array();
		
		// Recupera as classes de sistema.
		$recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		
		// Percorre as classes localizadas...
		foreach($recursiveIteratorIterator as $d) {
			// Verifica se est� nos padr�es para ser um ORM.
			if($d->isFile() && preg_match("/" . EntityData::COMPOSE_OBJECT_MAP . "$/", str_replace(".php", "", $d->getFileName()))) {
				$anotations = $this->parseClass(str_replace(".php", "", $d->getFileName()));
				if(!empty($anotations)) {
					if(!empty($anotations->foreignKeys)) {
						array_push($classList, $anotations);
					} else {
						array_unshift($classList, $anotations); 
					}
				}
			}
		}
		return $classList;
	}
	
	/**
	 * Recupera os dados de uma tabela de ORM de acordo com suas anota��es.
	 * 
	 * @param string $className Nome da classe ORM.
	 * @throws Exception
	 */
	public function parseClass($className) {
		// Consiste os dados.
		if(empty($className))
			throw new Exception("� necess�rio informar o nome da classe.");
		
		// Aplica a reflex�o na classe informada.
		$class = new ReflectionClass($className);
		
		// Aramzena a estrutura da classe.
		$entityData = null;
		
		// Recupera os coment�rios da classe...
		$docComment = EntityUtil::cleanComment($class->getDocComment());

		// Percorre as linhas...
		foreach(explode("\n", $docComment) as $line ) {
			// Verifica se tem uma anota��o de estrutura de dados ORM (tabela).
			if(preg_match("/^@(" . EntityData::ENTITY_TABLE . ")/", $line)) {
				// Inst�ncia da estrutura.
				$entityData = new EntityData();
				
				// Percorre os valores.
				foreach($this->requestParams($line) as $k => $v) {
					foreach ($entityData as $eK => $eV) {
						if($k == $eK) {
							$entityData->{$eK} = $v;
							break;
						}
					}	
				}

				// Percorre as propriedades publicas...
				foreach($class->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
					
					// Verifica se a propriedade pertence a classe.
					//
					// ATEN��O: Estruturas de dados (ORM) podem ser extendidas,
					// essa verifica��o evita que as colunas de uma tabela 
					// "parent" sejam criadas na tabela que a extendeu.
					if($prop->getDeclaringClass()->getName() !== $class->getName())
						continue;  
					
					// Recupera os dados.
					$data = $this->parseProperty($prop);
					
					if(!empty($data[EntityData::ENTITY_COLUMN])) {
						// Inclui as colunas. 
						array_push($entityData->columns, $data[EntityData::ENTITY_COLUMN]);	
						
						if(!empty($data[EntityData::ENTITY_FOREIGN_KEY]))
							// Inclui as chaves estrangeiras.
							array_push($entityData->foreignKeys, $data[EntityData::ENTITY_FOREIGN_KEY]);
					}
				}
			}
		}
		
		// Se n�o tem anota��o de ORM...
		if(empty($entityData))
			return null; 
		
		// Inclui o nome da tabela.
		$entityData->name = lcfirst(str_replace(EntityData::COMPOSE_OBJECT_MAP, "", $class->getName()));
		
		return $entityData;
	}
	
	/**
	 * Recupera os dados da coluna (EntityData::ENTITY_COLUMN).
	 * 
	 * @param ReflectionProperty $property Propriedade da estrutura de dados ORM
	 * @throws Exception
	 * @return array Vetor associtavo contento as estruturas da coluna.
	 */
	public function parseProperty(ReflectionProperty $property) {
		// Consiste o argumento.
		if(!($property instanceof ReflectionProperty))
			throw new Exception("� necess�rio informar uma estrutura de reflex�o da propriedade v�lida.");

		// Recupera o coment�rio.
		$parsedDocComment = EntityUtil::cleanComment($property->getDocComment());
		
		// Classe com a estrutura de dados da coluna.
		$column = null;
		
		// Classe com a estrutura de dados das chaves estrangeiras.
		$foreignKey = null;
		
		// Percorre as linhas...
		foreach(explode("\n", $parsedDocComment) as $line ){
			// Verifica se tem uma anota��o de "coluna"...
			if(preg_match("/^@(" . EntityData::ENTITY_COLUMN . ")/", $line)) {
				// Inst�ncia da estrutura de dados.
				$column = new EntityColumnData();
				
				// Percorre os valores
				foreach($this->requestParams($line) as $key => $value) {
					// Percorre a estrutura de dados...
					foreach($column as $cKey => $cValue) {
						if($cKey == $key) {
							if($value == "true" || $value == "false") {
								$column->{$cKey} = trim($value) == "true" ? true : false;
							} else {
								$column->{$cKey} = (string) $value;
							}
							break;
						}
					}
				}
				$column->name = $property->getName();
				continue;
			}
			
			// Verifica se tem de chave estrangeira.
			if(preg_match("/^@(" . EntityData::ENTITY_FOREIGN_KEY . ")/", $line)) {
				// Inst�ncia da estrutura de dados.
				$foreignKey = new EntityForeignKeyData();
				
				// Percorre os valores
				foreach($this->requestParams($line) as $key => $value) {
					// Percorre a estrutura de dados...
					foreach($foreignKey as $fKey => $fValue) {
						if($fKey == $key) {
							$foreignKey->{$fKey} = $value;
							break;
						}
					}
				}
				$foreignKey->name = $property->getName();
				continue;
			}
		}
		
		return array(
					EntityData::ENTITY_COLUMN => $column,
					EntityData::ENTITY_FOREIGN_KEY => $foreignKey
				);
	}
	
	/**
	 * Trata a anota��o da entidade retornando seus par�metros em um vetor 
	 * associativo.
	 * 
	 * @param string $line Linha referente a anota��o.
	 * @return array
	 */
	private function requestParams($line) {
		// Vari�vel de retorno.
		$out = array();
		
		// Verifica se tem argumento.
		if(!preg_match("/^@(.+)\((.+)\)/", $line))
			return $out;
		
		// Recupera os par�metros de configura��o da anota��o.
		$start = strpos(trim($line), "(")+1;
		$data = str_replace(array(" ", '"'), "", substr(trim($line), $start, strrpos(trim($line), ")")-$start));
		
		// Percorre os dados...
		foreach(explode(EntityColumnData::ARGUMENT_SEPARATOR, $data) as $d) {
			list($key, $value) = explode("=", $d);
			$out[$key] = $value;
		}
		return $out;
	}
}