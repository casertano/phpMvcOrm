<?php 
/**
 * Estrutura de dados para uma tabela atrav�s do ORM.
 * 	 
 * @author Andre
 */
class EntityData {
	
	/**
	 * Constante que identifica o nome da entidade respons�vel por indicar
	 * uma classe como tabela.
	 * @var string
	 */
	const ENTITY_TABLE = "table";
	
	/**
	 * Constnte que identifica o nome da entidade respons�vel por indicar uma 
	 * vari�vel como coluna da tabela.
	 * @var string
	 */
	const ENTITY_COLUMN = "column";
	
	/**
	 * Constnte que identifica o nome da entidade respons�vel por indicar uma
	 * vari�vel como chave estrangeira.
	 * @var string
	 */
	const ENTITY_FOREIGN_KEY = "foreignKey";
	
	/**
	 * Constante que identifica a composi��o do nome para a estrutura de um
	 * objeto ORM.
	 * @var string
	 */
	const COMPOSE_OBJECT_MAP = "Data";
	
	/**
	 * Nome da tabela.
	 * @var string
	 */
	public $name = "";
	
	/**
	 * Estrutura de suas respectivas colunas.
	 * @var EntityColumnData[]
	 */
	public $columns = array();
	
	/**
	 * Estrutura das chaves estrangeiras.
	 * @var EntityForeignKeyData[]
	 */
	public $foreignKeys = array();
}
?>