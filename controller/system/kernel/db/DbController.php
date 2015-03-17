<?php
/**
 * Classe respons�vel pela instala��o e procedimentos do sistema no banco de
 * dados.
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class DbController {
	
	/**
	 * Executa a instala��o das tabelas 
	 */
	public function installTables() {
		
		// Recupera a lista com as estruturas de tabela...
		$entityManager = new EntityManager();
		$tables  = $entityManager->requestDataTables();
		
		// Inst�ncia da classe de instala��o do banco de dados.
		$dbInstall = new DbInstall();
		$dbInstall->install($tables);
		
		return new View();
	}
}