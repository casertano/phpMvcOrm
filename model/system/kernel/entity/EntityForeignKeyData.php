<?php 
class EntityForeignKeyData {
	
	/**
	 * Nome do campo que ser� uma chave estrangeira.
	 * @var string
	 */
	public $name = "";
	
	/**
	 * Tabela de refer�ncia para a chave estrangeira.
	 * @var string
	 */
	public $references = "";
	
	/**
	 * Chave para refer�nca na tabela de refer�ncia.
	 * @var string
	 */
	public $key = "";
	
	/**
	 * A��o quando executar um update.
	 * @var string
	 */
	public $onUpdate = "restrict";
	
	/**
	 * A��o quando executar uma exclus�o.
	 * @var string
	 */
	public $onDelete = "restrict";
}
?>