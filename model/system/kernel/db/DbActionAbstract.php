<?php
/**
 * Classe respons�vel pelo controle das a��es referente ao CRUD (acr�nimo de 
 * Create, Read, Update e Delete).
 * 
 * Essa classe executas as a��es referente ao CRUD de forma din�mica de 
 * acordo com a estrutura de dados forneciada ao m�todo e o nome da classe
 * que � abstraiu.
 * 
 * ATEN��O: O nome da CLASSE utilizada na abstra��o deve ser exatamente o 
 * nome da estrutura de dados que ela trata sem o "Data" e a chave prim�ria
 * para a tabela deve ser a palavra "id" + "Nome da classe", ou seja:
 * 
 * Para uma estrutura de dados de contato: ContactData
 * Classe para controle de opera��es no BD: Contact
 * Chave prim�ria para a tabela: idContact
 * 
 * Para uma estrutura de dados de telefones do contato: ContactPhoneData
 * Classe para controle de opera��es no BD: ContactPhone
 * Chave prim�ria para a tabela: idContactPhone
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
	 * Nome da chave prim�ria.
	 */
	private $primaryKey;
	
	/**
	 * Construtor.
	 * Cria a conex�o com o banco de dados e identifica a tabela e a estrutura
	 * de dados.
	 */
	public function __construct() {
		// Construtor da classe pai.
		parent::__construct();
		
		// Recupera o nome da classe.
		$this->tableName = lcfirst(get_called_class());
		
		// Comp�e o nome da estrutura de dados.
		$this->data = get_called_class() . EntityData::COMPOSE_OBJECT_MAP;
		
		// Verifica se a esrutura de dados da classe existe.
		try {
			class_exists($this->data);
		} catch (Exception $e) {
			throw new Exception("N�o foi poss�vel localizar a estrutura de dados (" . $this->data . ") para a classe '" . get_called_class() . "'.");
		}
		
		// Comp�e o nome da chave prim�ria.
		$this->primaryKey = "id" . get_called_class();
	}
	
	/**
	 * Recupera a estrutura de dados de acordo com o c�digo (chave prim�ria) 
	 * informado.
	 * 
	 * @param int $id 	C�digo do item.
	 * @throws Exception
	 * @return object Estrutura de dados da informa��o solicitada.
	 */
	public function getData($id) {
		
		// Consiste o par�metro.
		if(!is_numeric($id) || $id <= 0)
			throw new Exception("� necess�rio informar um c�digo v�lido.");
		
		// Prepara a sele��o.
		$qry = $this->getConn()->prepare(
				"SELECT * FROM " . $this->tableName .
				" WHERE " . $this->primaryKey . " = :id "
			);
		
		// Executa a sele��o.
		if(!$qry->execute(array("id" => $id))) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}
		
		// Retorna os dados de acordo com a estrutura.
		return $qry->fetchObject($this->data);
	}
	
	/**
	 * Faz a inclus�o dos dados de acordo com a estrutura de dados informada.
	 * 
	 * @param object $data Estrutura de dados para a inclus�o.
	 * @throws Exception
	 * @return int C�digo da inclus�o (chave prim�ria)
	 */
	public function insert($data) {
		
		// Consiste a estrutura de dados.
		if(!($data instanceof $this->data))
			throw new Exception("� necess�rio informar uma estrutura v�lida (" . $this->data . ") para a tabela '" . $this->tableName . "'.");
		
		// Armazena os par�metros para inclus�o.
		$params = array();
		
		// Armazena o nome das colunas.
		$colName = array();
		
		// Separa os dados de inclus�o.
		foreach($data as $k => $v) {
			
			// Se for a chave prim�ria n�o inclui na lista.
			if($k == $this->primaryKey)
				continue;
			
			// Par�metros com seus respectivos valores.
			$params[$k] = $v;
			
			// Nome das colunas.
			array_push($colName, $k);
		}
		
		// Prepara a inclus�o.
		$qry = $this->getConn()->prepare(
			"INSERT INTO " . $this->tableName . " ( " .
			implode(",", $colName) . 
			" ) VALUES ( " .
			":" . implode(", :", $colName) . ")"
		);
		
		// Executa a inclus�o.
		if(!$qry->execute($params)) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}  
		
		return $this->getConn()->lastInsertId();
	}
	
	/**
	 * Faz a edi��o dos dados de acordo com a estrutura informada.
	 * 
	 * @param object $data Estrutura de dados para edi��o.
	 * @throws Exception
	 */
	public function edit($data) {
		
		// Consiste a estrutura de dados.
		if(!($data instanceof $this->data))
			throw new Exception("� necess�rio informar uma estrutura v�lida (" . $this->data . ") para a tabela '" . $this->tableName . "'.");
		
		// Armazena os par�metros para inclus�o.
		$params = array();
		
		// Armazena o nome das colunas.
		$col = array();
		
		// Separa os dados para edi��o.
		foreach($data as $k => $v) {

			// Par�metros com seus respectivos valores.
			$params[$k] = $v;

			// Se N�O for a chave prim�ria...
			if($k != $this->primaryKey) {
				// ... Armazena o nome da coluna.
				array_push($col, $k . " = :" . $k);
			}
		}
		
		// Prepara a edi��o.
		$qry = $this->getConn()->prepare(
			"UPDATE " . $this->tableName . " SET " .
			implode(",", $col) .
			" WHERE " . $this->primaryKey . " = :" . $this->primaryKey
		);
		
		// Executa a edi��o.
		if(!$qry->execute($params)) {
			$err = $qry->errorInfo();
			throw new Exception($err[2]);
		}
		
		return $data->{$this->primaryKey};
	}
} 
?>