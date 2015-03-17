<?php
/**
 * @table
 * @author Andre
 *
 */
class TestBData {
	
	/**
	 * Código
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idTestB = 0;
	
	/**
	 * Código do conteúdo.
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="content"; key="idContent"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idContent = 0;
	
	/**
	 * Código do conteúdo.
	 * @column(type="integer"; notNull="true"; unsigned="true")
	 * @foreignKey(references="testC"; key="idTestC"; onUpdate="restrict"; onDelete="cascade")
	 * @var int
	 */
	public $idTestC = 0;
	
	/**
	 * Teste para chave única.
	 * @column(type="char(10)"; notNull="true"; uniqueKey="true")
	 * @var string
	 */
	public $cpf = "";
	
}