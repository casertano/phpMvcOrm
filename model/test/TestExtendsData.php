<?php
/**
 * @table
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class TestExtendsData {
	
	/**
	 * C�digo
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idTestExtends = 0;
	
	/**
	 * @column(type="varchar(250)"; notNull="true")
	 * @var string
	 */
	public $description = "";
	
	/**
	 * C�digo
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="testB"; key="idTestB"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idTestB = 0;
}