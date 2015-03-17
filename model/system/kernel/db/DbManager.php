<?php 
/**
 * Classe singleton respons�vel por recuperar a inst�ncia da classe correspondente 
 * ao protocolo de banco de dados configurado.
 * Esta classe faz o gerenciamento da conex�o usada no sistema.
 * 
 * Ex:
 * Para um protocolo MySQL sua classe deve ser DbMysql.
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class DbManager {
	
	/**
	 * Armazena a inst�ncia da classe.
	 * @var object
	 */
	private static $dbProtocol;
	
	/**
	 * Construtor.
	 */
	private function __construct() {}
	
	/**
	 * Retorna a inst�ncia da classe respons�vel pelo protocolo de tarefas comuns
	 * do sistema com o banco de dados.
	 * Ex: mysql => DbMysql, mssql => DbMssql
	 * 
	 * @return DbManagerAbstract Inst�ncia da classe que abstraiu DbManagerAbstract.
	 */
	public static function getInstance() {
		if(!isset(self::$dbProtocol)) {
			// Recupera o protocolo utilizado.
			$protocol = "Db".ucfirst(DbConnectManager::getProtocolName());
			// Faz a inst�ncia da classe.
			eval("self::\$dbProtocol = new \$protocol();");
		}
		return self::$dbProtocol;
	}
	
	/**
	 * Evita a clonagem.
	 */
	public function __clone() {
		throw new Exception("N�o � poss�vel clonar essa classe.");
	}
}
?>