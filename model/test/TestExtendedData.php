<?php
/**
 * @table
 * @author Andr� Casertano <andre@casertano.com.br>
 *
 */
class TestExtendedData extends TestExtendsData{
	
	/**
	 * C�digo
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idTestExtended = 0;
	
	/**
	 * C�digo
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="testExtends"; key="idTestExtends"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idTestExtends = 0;
}