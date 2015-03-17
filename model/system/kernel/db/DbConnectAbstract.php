<?php
/**
 * Classe respons�vel por fornecer uma conex�o abstrata para o banco de dados.
 * 
 * @author Andre
 */
abstract class DbConnectAbstract {
	
	/**
	 * Armazena a conex�o com o banco de dados.
	 */
	private $conn;
	
	/**
	 * Recupera o objeto de conex�o com o banco de dados.
	 * 
	 * @see PDO (http://php.net/manual/pt_BR/book.pdo.php)
	 * @return PDO
	 */
	public function getConn() {
		return $this->conn; 
	}
	
	/**
	 * Construtor.
	 * Fornece uma conex�o abstrata com o banco de dados.
	 */
	public function __construct() {
		$this->conn = DbConnectManager::getInstance();
	}
}