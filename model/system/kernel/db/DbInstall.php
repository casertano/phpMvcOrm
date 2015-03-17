<?php
/**
 * Classe respons�vel pela instala��o do banco de dados.
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class DbInstall {
	
	/**
	 * Armazena as tabelas que aguardam dependencia.
	 */
	private $waitParent = array();
	
	/**
	 * Armazena a inst�ncia do banco de dados de acordo com o protocolo
	 * informado na configura��o.
	 */
	private $db;
	
	/**
	 * Construtor
	 */
	public function __construct() {
		$this->db = DbManager::getInstance();
	}
	
	/**
	 * Faz a instala��o das tabelas ORM do sistema.
	 * 
	 * @param EntityData[] $dataBaseList Vetor contendo a estrutura EntityData 
	 * para instala��o das tabelas do sistema.
	 * @throws Exception
	 */
	public function install(array $dataBaseList) {
		
		// Consiste o par�metro.
		if(!is_array($dataBaseList))
			throw new Exception("� necess�rio informar uma estrutura de tabelas v�lida.");

		// Inicia o processo de instala��o.
		$this->callInstall($dataBaseList);
	}
	
	/**
	 * Reordena a lista de acordo com suas depend�ncias (foreignkeys)
	 * 
	 * @param EntityData[] $dataBaseList Lista contento as estruturas para cria��o
	 * do banco de dados.
	 */
	private function callInstall($dataBaseList) {
		
		// Percorre a lista de estruturas...
		foreach ($dataBaseList as $d) {
			// Verifica se a estrutura � uma EntityData...
			if(!($d instanceof EntityData))
				throw new Exception("Para a instala��o do banco de dados � necess�rio informar uma estrutura ORM (EntityData[]) v�lida.");
			
			// Flag indicando se pode instalar.
			$canInstall = true;
			
			// Flag indicando que uma tabela foi instalada.
			$hasInstalled = false;
			
			// Se tem dependencias...
			if(!empty($d->foreignKeys)) {
				// ... Percorre as depend�ncias...
				foreach ($d->foreignKeys as $k) {
					// Se a tabela "pai" n�o existe...
					if(!$this->db->tableExist($k->references)) {
						$canInstall = false;
						break;
					}
				}
			}
			
			// Se n�o pode instalar...
			if(!$canInstall) {
				// Armazena na lista de espera...
				array_push($this->waitParent, $d);
			} else {
				// Faz a "constru��o da tabela"...
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
		
		// Se tem tabelas aguardando instala��o, mas nenhuma foi instalada...
		if(!$hasInstalled && !empty($this->waitParent)) {
			// ... Significa que uma tabela dependente n�o foi localizada.
			
			// Armazena as tabelas que n�o foram instaladas.
			$notInstalled = array();
			
			// Percorre a lista de tabelas.
			foreach($this->waitParent as $w) {
				// Armazena suas depend�ncias ("chaves estrangeiras").
				$fKeys = array();
				
				// Percorre as depend�ncias...
				foreach($w->foreignKeys as $k)
					array_push($fKeys, $k->references);

				// Comp�e o nome da tabela n�o instalada e suas depend�ncias...
				$notInstalled[] = $w->name . " (" . implode(", ", $fKeys) . ") ";
			}
			
			throw new Exception("Algumas tabelas n�o foram instaladas pois n�o 
					foi poss�vel encontrar suas depend�ncias. \nS�o elas: \n" . 
					implode('\n', $notInstalled));
			
		// Se tem tabelas aguardando a instala��o...
		} else if(!empty($this->waitParent)) {
			// ... Armazena os adados...
			$tmpList = $this->waitParent;
			
			// ... Limpa a lista...
			$this->waitParent = array();
			// ... Refaz a instala��o.
			$this->callInstall($tmpList);
		}
	}
}