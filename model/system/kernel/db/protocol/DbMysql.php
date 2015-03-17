<?php
/**
 * Classe responsável por gerenciar as requisições para o banco de dados MySQL.
 *  
 * @author André Casertano <andre@casertano.com.br>
 */
class DbMysql extends DbManagerAbstract {
	
	/**
	 * (non-PHPdoc)
	 * @see DbManagerAbstract::createTable()
	 */
	public function createTable(EntityData $entityData) {
		// Consiste o argumento.
		if(!($entityData instanceof EntityData))
			throw new Exception("É necessário informar uma estrutura de dados válida para a criação da tabela de dados.");
		
		// Armazena as chaves primárias.
		$primaryKeys = array();
		
		// Armazena as chaves únicas.
		$uniqueKeys = array();
		
		// Armazena as colunas.
		$column = array();
		
		// Armazena os dados da tabela.
		$table = "CREATE TABLE IF NOT EXISTS " . $entityData->name . " ( ";
		
		// Percorre as colunas.
		foreach ($entityData->columns as $c => $v) {
			// Inclui o nome da coluna.
			$col = $v->name;
			
			// Inclui o tipo.
			$col .= " " . (string) $v->type;
			
			// Se não pode ser menor que zero.
			if($v->unsigned)
				$col .= " UNSIGNED";
			
			// Se não deve ser nulo...
			if($v->notNull)
				$col .= " NOT NULL";
			
			// Se é "auto-increment"...
			if($v->autoIncrement)
				$col .= " AUTO_INCREMENT";
			
			$col .= " COMMENT ''";
			
			// Inclui a coluna.
			array_push($column, $col);
			
			// Se é uma chave primária...
			if($v->primaryKey)
				array_push($primaryKeys, $v->name);
			
			// Se é uma chave única...
			if($v->uniqueKey)
				array_push($uniqueKeys, $v->name);
		}
		
		// Inclui as colunas.
		$table .= implode(",", $column);
		
		// Se tem dados para chave primária...
		if(!empty($primaryKeys))
			// ... Inclui.
			$table .= ", PRIMARY KEY(" . implode(",", $primaryKeys) . ")";
		
		// Se temdados de chaves únicas...
		if(!empty($uniqueKeys))
			// ... Inclui.
			$table .= ", UNIQUE KEY(" . implode(",", $uniqueKeys) . ")";
			
		// Se tem dados de chave estrangeira...
		if(!empty($entityData->foreignKeys)) {
			
			// Armazena as chaves estrangeiras.
			$foreignKeys = array();
			
			// Percorre os dados de chave estrangeira.
			foreach ($entityData->foreignKeys as $k => $d) {
				// Nome da chave...
				$fk = " FOREIGN KEY( " . $d->name . ") ";
				
				// Inclui as referências.
				$fk .= " REFERENCES " . $d->references . "(" . $d->key . ") ";
				$fk .= " ON DELETE " . $d->onDelete;
				$fk .= " ON UPDATE " . $d->onUpdate;
				
				// Adiciona os dados.
				array_push($foreignKeys, $fk);
			}
			
			// Inclui as chaves estrangeiras.
			$table .= "," . implode(",", $foreignKeys);
		}
		
		$table .= " ) " .
				  " ENGINE = InnoDB DEFAULT ".
				  " CHARSET = utf8; " ;
		
		return $table;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DbManagerAbstract::tableExist()
	 */
	public function tableExist($tableName) {
		// Consiste o argumento.
		if(empty($tableName)) 
			throw new Exception("É necessário informar um nome válido para o banco de dados.");
		
		// Prepara a seleção da tabela.
		$qry = $this->getConn()->prepare(
			"SELECT * " .
			"FROM information_schema.tables " .
			"WHERE table_schema = :table_schema " . 
    		"AND table_name = :table_name " .
			"LIMIT 1 "		
		);
		
		// Faz a seleção da tabela.
		if(!$qry->execute(array("table_schema" => DbConnectManager::getDbName(), "table_name" => $tableName))) {
			$error = $qry->errorInfo();
			throw new Exception($error[2]);
		}
		$result = $qry->fetch();
		
		return !empty($result);
	}
}