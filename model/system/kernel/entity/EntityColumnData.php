<?php
/**
 * Estrutura de dados para uma coluna.
 * 
 * @author Andre
 */ 
class EntityColumnData {
	
	/**
	 * Identifica o separador de argumentos para a coluna.
	 */
	const ARGUMENT_SEPARATOR = ";";
	
	/**
	 * Nome da coluna
	 * @var string
	 */
	public $name = "";
	
	/**
	 * Tipo de dado.
	 * @var string
	 */
	public $type = "";
	
	/**
	 * Indica se é uma chave primária.
	 * @var boolean
	 */
	public $primaryKey = false;
	
	/**
	 * Indica se é uma chave única.
	 * @var boolean
	 */
	public $uniqueKey = false;
	
	/**
	 * Indica se é incrementado automaticamente.
	 * @var boolean
	 */
	public $autoIncrement = false;
	
	/**
	 * Indica se pode ser nulo.
	 * @var boolean
	 */
	public $notNull = false;
	
	/**
	 * Verifica se permite valores negativos.
	 * @var boolean
	 */
	public $unsigned = false;
	
	/**
	 * Indica o valor padrão.
	 * @var string
	 */
	public $default = "";
}
?>