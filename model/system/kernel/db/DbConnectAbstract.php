<?php
/**
 * Classe responsável por fornecer uma conexão abstrata para o banco de dados.
 * 
 * @author Andre
 */
abstract class DbConnectAbstract {
	
	/**
	 * Armazena a conexão com o banco de dados.
	 */
	private $conn;
	
	/**
	 * Recupera o objeto de conexão com o banco de dados.
	 * 
	 * @see PDO (http://php.net/manual/pt_BR/book.pdo.php)
	 * @return PDO
	 */
	public function getConn() {
		return $this->conn; 
	}
	
	/**
	 * Construtor.
	 * Fornece uma conexão abstrata com o banco de dados.
	 */
	public function __construct() {
		$this->conn = DbConnectManager::getInstance();
	}
}