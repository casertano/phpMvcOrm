<?php 
/**
 * Classe singleton responsável por recuperar a instância da classe correspondente 
 * ao protocolo de banco de dados configurado.
 * Esta classe faz o gerenciamento da conexão usada no sistema.
 * 
 * Ex:
 * Para um protocolo MySQL sua classe deve ser DbMysql.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
class DbManager {
	
	/**
	 * Armazena a instância da classe.
	 * @var object
	 */
	private static $dbProtocol;
	
	/**
	 * Construtor.
	 */
	private function __construct() {}
	
	/**
	 * Retorna a instância da classe responsável pelo protocolo de tarefas comuns
	 * do sistema com o banco de dados.
	 * Ex: mysql => DbMysql, mssql => DbMssql
	 * 
	 * @return DbManagerAbstract Instância da classe que abstraiu DbManagerAbstract.
	 */
	public static function getInstance() {
		if(!isset(self::$dbProtocol)) {
			// Recupera o protocolo utilizado.
			$protocol = "Db".ucfirst(DbConnectManager::getProtocolName());
			// Faz a instância da classe.
			eval("self::\$dbProtocol = new \$protocol();");
		}
		return self::$dbProtocol;
	}
	
	/**
	 * Evita a clonagem.
	 */
	public function __clone() {
		throw new Exception("Não é possível clonar essa classe.");
	}
}
?>