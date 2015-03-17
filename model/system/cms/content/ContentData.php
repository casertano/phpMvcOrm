<?php 
/**
 * @table
 * @author Andre
 */
class ContentData {
	
	/**
	 * C�digo do conte�do.
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idContent = 0;
	
	/**
	 * T�tulo
	 * @column(type="varchar(50)"; primaryKey="false"; autoIncrement="false"; notNull="true"; unsigned="false")
	 * @var int
	 */
	public $title = "";
	
	/**
	 * Descri��o do conte�do
	 * @column(type="text"; primaryKey="false"; autoIncrement="false"; notNull="false"; unsigned="false")
	 * @var int
	 */
	public $text = "";
}
?>