<?php
/**
 * Classe responsável pelo controle das ações referente ao CRUD (acrônimo de 
 * Create, Read, Update e Delete).
 * 
 * Essa classe executas as ações referente ao CRUD de forma dinâmica de 
 * acordo com a estrutura de dados forneciada ao método e o nome da classe
 * que à abstraiu.
 * 
 * ATENÇÃO: O nome da CLASSE utilizada na abstração deve ser exatamente o 
 * nome da estrutura de dados que ela trata sem o "Data" e a chave primária
 * para a tabela deve ser a palavra "id" + "Nome da classe", ou seja:
 * 
 * Para uma estrutura de dados de contato: ContactData
 * Classe para controle de operações no BD: Contact
 * Chave primária para a tabela: idContact
 * 
 * Para uma estrutura de dados de telefones do contato: ContactPhoneData
 * Classe para controle de operações no BD: ContactPhone
 * Chave primária para a tabela: idContactPhone
 * 
 * @author Andre
 */ 
abstract class DbActionAbstract extends DbConnectAbstract {
	
	/**
	 * Armazena o nome da tabela.
	 */
	private $tableName;
	
	/**
	 * Armazena o nome da estrutura de dados da tabela.
	 */
	private $data;
	
	/**
	 * Nome da chave primária.
	 */
	private $primaryKey;
	
	/**
	 * Construtor.
	 * Cria a conexão com o banco de dados e identifica a tabela e a estrutura
	 * de dados.
	 */
	public function __construct() {
		// Construtor da classe pai.
		parent::__construct();
		
		// Recupera o nome da classe.
		$this->tableName = lcfirst(get_called_class());
		
		// Compõe o nome da estrutura de dados.
		$this->data = get_called_class() . EntityData::COMPOSE_OBJECT_MAP;
		
		// Verifica se a esrutura de dados da classe existe.
		try {
			class_exists($this->data);
		} catch (Exception $e) {
			throw new Exception("Não foi possível localizar a estrutura de dados (" . $this->data . ") para a classe '" . get_called_class() . "'.");
		}
		
		// Compõe o nome da chave primária.
		$this->primaryKey = "id" . get_called_class();
	}
	
	/**
	 * Recupera a estrutura de dados de acordo com o código (chave primária) 
	 * informado.
	 * 
	 * @param int $id 	Código do item.
	 * @throws Exception
	 * @return object Estrutura de dados da informação solicitada.
	 */
	public function getData($id) {
		
		// Consiste o parâmetro.
		if(!is_numeric($id) || $id <= 0)
			throw new Exception("É necessário informar um código válido.");
		
		// Prepara a seleção.
		$qry = $this->getConn()->prepare(
				"SELECT * FROM " . $this->tableName .
				" WHERE " . $this->primaryKey . " = :id "
			);
		
		// Executa a seleção.
		if(!$qry->execute(array("id" => $id))) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}
		
		// Retorna os dados de acordo com a estrutura.
		return $qry->fetchObject($this->data);
	}
	
	/**
	 * Faz a inclusão dos dados de acordo com a estrutura de dados informada.
	 * 
	 * @param object $data Estrutura de dados para a inclusão.
	 * @throws Exception
	 * @return int Código da inclusão (chave primária)
	 */
	public function insert($data) {
		
		// Consiste a estrutura de dados.
		if(!($data instanceof $this->data))
			throw new Exception("É necessário informar uma estrutura válida (" . $this->data . ") para a tabela '" . $this->tableName . "'.");
		
		// Armazena os parâmetros para inclusão.
		$params = array();
		
		// Armazena o nome das colunas.
		$colName = array();
		
		// Separa os dados de inclusão.
		foreach($data as $k => $v) {
			
			// Se for a chave primária não inclui na lista.
			if($k == $this->primaryKey)
				continue;
			
			// Parâmetros com seus respectivos valores.
			$params[$k] = $v;
			
			// Nome das colunas.
			array_push($colName, $k);
		}
		
		// Prepara a inclusão.
		$qry = $this->getConn()->prepare(
			"INSERT INTO " . $this->tableName . " ( " .
			implode(",", $colName) . 
			" ) VALUES ( " .
			":" . implode(", :", $colName) . ")"
		);
		
		// Executa a inclusão.
		if(!$qry->execute($params)) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}  
		
		return $this->getConn()->lastInsertId();
	}
	
	/**
	 * Faz a edição dos dados de acordo com a estrutura informada.
	 * 
	 * @param object $data Estrutura de dados para edição.
	 * @throws Exception
	 */
	public function edit($data) {
		
		// Consiste a estrutura de dados.
		if(!($data instanceof $this->data))
			throw new Exception("É necessário informar uma estrutura válida (" . $this->data . ") para a tabela '" . $this->tableName . "'.");
		
		// Armazena os parâmetros para inclusão.
		$params = array();
		
		// Armazena o nome das colunas.
		$col = array();
		
		// Separa os dados para edição.
		foreach($data as $k => $v) {

			// Parâmetros com seus respectivos valores.
			$params[$k] = $v;

			// Se NÃO for a chave primária...
			if($k != $this->primaryKey) {
				// ... Armazena o nome da coluna.
				array_push($col, $k . " = :" . $k);
			}
		}
		
		// Prepara a edição.
		$qry = $this->getConn()->prepare(
			"UPDATE " . $this->tableName . " SET " .
			implode(",", $col) .
			" WHERE " . $this->primaryKey . " = :" . $this->primaryKey
		);
		
		// Executa a edição.
		if(!$qry->execute($params)) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}
		
		return $data->{$this->primaryKey};
	}
} 
?>