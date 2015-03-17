<?php
/**
 * Classe responsável pela instalação e procedimentos do sistema no banco de
 * dados.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
class DbController {
	
	/**
	 * Executa a instalação das tabelas 
	 */
	public function installTables() {
		
		// Recupera a lista com as estruturas de tabela...
		$entityManager = new EntityManager();
		$tables  = $entityManager->requestDataTables();
		
		// Instância da classe de instalação do banco de dados.
		$dbInstall = new DbInstall();
		$dbInstall->install($tables);
		
		return new View();
	}
}