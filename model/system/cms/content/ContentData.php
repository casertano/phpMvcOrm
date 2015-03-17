<?php 
/**
 * @table
 * @author Andre
 */
class ContentData {
	
	/**
	 * Código do conteúdo.
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idContent = 0;
	
	/**
	 * Título
	 * @column(type="varchar(50)"; primaryKey="false"; autoIncrement="false"; notNull="true"; unsigned="false")
	 * @var int
	 */
	public $title = "";
	
	/**
	 * Descrição do conteúdo
	 * @column(type="text"; primaryKey="false"; autoIncrement="false"; notNull="false"; unsigned="false")
	 * @var int
	 */
	public $text = "";
}
?>