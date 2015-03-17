<?php 
/**
 * Classe singleton para gerкnciamento da conexгo.
 * 
 * @see PDO (http://php.net/manual/pt_BR/book.pdo.php) 
 * @author Andrй Casertano <andre@casertano.com.br>
 */
class DbConnectManager {
	
	/**
	 * Armazena a instвncia da classe.
	 */
	private static $dbConnectManager;
	
	/**
	 * Armazena o nome do protocolo (mysql, mssql, etc...)
	 */
	private static $protocol;
	
	/**
	 * Armazena o nome do banco de dados.
	 */
	private static $dbname;
	
	/**
	 * Construtor
	 */
	private function __construct() {}
	
	/**
	 * Recupera a instвncia da conexгo.
	 * 
	 * Os dados para a conexгo sгo recuperados do arquivo de configuraзгo 
	 * config/database.xml
	 * 
	 * @throws Exception
	 * @return PDO
	 */
	public static function getInstance() {
		if(!isset(self::$dbConnectManager)) {
			try {
				// Carrega os parвmetros de conexгo com o banco de dados
				$xml = simplexml_load_file(Application::getPath() . Application::DIR_CONFIG . DIRECTORY_SEPARATOR . "database.xml");
				
				// Armazena os parвmetros para a conexгo com o banco de dados
				self::$protocol = strtolower((string)$xml->protocol);
				$host = (string)$xml->host;
				$port = (int)$xml->port;
				self::$dbname = (string)$xml->schema;
				$username = (string)$xml->userName;
				$passwd = (string)$xml->passwd;
				
				// Cria o "data source name".
				$dsn = self::$protocol . ":dbname=" . self::$dbname . ";host=" . $host;
				 
				// Instвncia da conexгo PDO;
				self::$dbConnectManager = new PDO($dsn, $username, $passwd);
			} catch(Exception $e) {
				throw new Exception("Nгo foi possнvel efetuar a conexгo com o banco de dados.
						Por favor verifique os dados de configuraзгo em 'config/database.xml'.");
			}
		}
		return self::$dbConnectManager;
	}
	
	/**
	 * Recupera o nome do protocolo utilizado como base de dados. 
	 * Ex: mysql, mssql, sqlite, etc.
	 * 
	 * @return string Nome do protocolo.
	 */
	public static function getProtocolName() {
		if(!empty(self::$protocol)) {
			return self::$protocol;
		} else {
			// Carrega os parвmetros de conexгo com o banco de dados
			$xml = simplexml_load_file(Application::getPath() . Application::DIR_CONFIG . DIRECTORY_SEPARATOR . "database.xml");
			return strtolower((string)$xml->protocol);
		}
	}
	
	/**
	 * Recupera o nome do banco de dados utilizado na aplicaзгo.
	 * 
	 * @return string Nome do banco de dados.
	 */
	public static function getDbName() {
		if(!empty(self::$dbname)) {
			return self::$dbname;
		} else {
			// Carrega os parвmetros de conexгo com o banco de dados
			$xml = simplexml_load_file(Application::getPath() . Application::DIR_CONFIG . DIRECTORY_SEPARATOR . "database.xml");
			return (string)$xml->schema;
		}
	}
	
	/**
	 * Nгo permite a clonagem.
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception("A operaзгo de clonagem nгo й permitida para essa classe!");
	}
}

?>