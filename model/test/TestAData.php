<?php 
/**
 * @table
 * @author Andre
 *
 */
class TestAData {
	
	/**
	 * Código
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idTestA = 0;
	
	/**
	 * Código do conteúdo.
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="testB"; key="idTestB"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idTestB = 0;
}

?>