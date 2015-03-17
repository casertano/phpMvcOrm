<?php 
/**
 * Estrutura de dados da imagem.
 * 
 * @table
 * @author Andre
 */
class ContentImageData {
	
	/**
	 * C�digo da imagem.
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idImage = 0;
	
	/**
	 * C�digo do conte�do.
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="content"; key="idContent"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idContent = 0;
	
	/**
	 * Extens�o da imagem.
	 * @column(type="char(5)"; primaryKey="false"; autoIncrement="false"; notNull="true")
	 * @var string
	 */
	public $extension = "";
	
	/**
	 * Caminho para a imagem.
	 * @var string
	 */
	public $url = "";
}
?>