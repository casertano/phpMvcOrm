<?php 
class EntityForeignKeyData {
	
	/**
	 * Nome do campo que serс uma chave estrangeira.
	 * @var string
	 */
	public $name = "";
	
	/**
	 * Tabela de referъncia para a chave estrangeira.
	 * @var string
	 */
	public $references = "";
	
	/**
	 * Chave para referъnca na tabela de referъncia.
	 * @var string
	 */
	public $key = "";
	
	/**
	 * Aчуo quando executar um update.
	 * @var string
	 */
	public $onUpdate = "restrict";
	
	/**
	 * Aчуo quando executar uma exclusуo.
	 * @var string
	 */
	public $onDelete = "restrict";
}
?>