<?php
/**
 * Classe responsável pela instalação do banco de dados.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
class DbInstall {
	
	/**
	 * Armazena as tabelas que aguardam dependencia.
	 */
	private $waitParent = array();
	
	/**
	 * Armazena a instância do banco de dados de acordo com o protocolo
	 * informado na configuração.
	 */
	private $db;
	
	/**
	 * Construtor
	 */
	public function __construct() {
		$this->db = DbManager::getInstance();
	}
	
	/**
	 * Faz a instalação das tabelas ORM do sistema.
	 * 
	 * @param EntityData[] $dataBaseList Vetor contendo a estrutura EntityData 
	 * para instalação das tabelas do sistema.
	 * @throws Exception
	 */
	public function install(array $dataBaseList) {
		
		// Consiste o parâmetro.
		if(!is_array($dataBaseList))
			throw new Exception("É necessário informar uma estrutura de tabelas válida.");

		// Inicia o processo de instalação.
		$this->callInstall($dataBaseList);
	}
	
	/**
	 * Reordena a lista de acordo com suas dependências (foreignkeys)
	 * 
	 * @param EntityData[] $dataBaseList Lista contento as estruturas para criação
	 * do banco de dados.
	 */
	private function callInstall($dataBaseList) {
		
		// Percorre a lista de estruturas...
		foreach ($dataBaseList as $d) {
			// Verifica se a estrutura é uma EntityData...
			if(!($d instanceof EntityData))
				throw new Exception("Para a instalação do banco de dados é necessário informar uma estrutura ORM (EntityData[]) válida.");
			
			// Flag indicando se pode instalar.
			$canInstall = true;
			
			// Flag indicando que uma tabela foi instalada.
			$hasInstalled = false;
			
			// Se tem dependencias...
			if(!empty($d->foreignKeys)) {
				// ... Percorre as dependências...
				foreach ($d->foreignKeys as $k) {
					// Se a tabela "pai" não existe...
					if(!$this->db->tableExist($k->references)) {
						$canInstall = false;
						break;
					}
				}
			}
			
			// Se não pode instalar...
			if(!$canInstall) {
				// Armazena na lista de espera...
				array_push($this->waitParent, $d);
			} else {
				// Faz a "construção da tabela"...
				$table = $this->db->createTable($d);
				
				// Instala a tabela...
				$qry = $this->db->getConn()->prepare($table);
				if(!$qry->execute()) {
					$err = $qry->errorInfo();
					throw new Exception("Erro ao instalar a tabela '" . $d->name . "' \n(" . $err[2] . ").");
				}
				// Indica que uma tabela foi instalada.
				$hasInstalled = true;
			}
		}
		
		// Se tem tabelas aguardando instalação, mas nenhuma foi instalada...
		if(!$hasInstalled && !empty($this->waitParent)) {
			// ... Significa que uma tabela dependente não foi localizada.
			
			// Armazena as tabelas que não foram instaladas.
			$notInstalled = array();
			
			// Percorre a lista de tabelas.
			foreach($this->waitParent as $w) {
				// Armazena suas dependências ("chaves estrangeiras").
				$fKeys = array();
				
				// Percorre as dependências...
				foreach($w->foreignKeys as $k)
					array_push($fKeys, $k->references);

				// Compõe o nome da tabela não instalada e suas dependências...
				$notInstalled[] = $w->name . " (" . implode(", ", $fKeys) . ") ";
			}
			
			throw new Exception("Algumas tabelas não foram instaladas pois não 
					foi possível encontrar suas dependências. \nSão elas: \n" . 
					implode('\n', $notInstalled));
			
		// Se tem tabelas aguardando a instalação...
		} else if(!empty($this->waitParent)) {
			// ... Armazena os adados...
			$tmpList = $this->waitParent;
			
			// ... Limpa a lista...
			$this->waitParent = array();
			// ... Refaz a instalação.
			$this->callInstall($tmpList);
		}
	}
}