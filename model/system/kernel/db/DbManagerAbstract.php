<?php
/**
 * Gerencia as rotinas de banco de dados.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
abstract class DbManagerAbstract extends DbConnectAbstract {
	
	/**
	 * Cria uma tabela de acordo com a estrutura ORM informada.
	 * 
	 * @param EntityData $entityData Estrutura ORM para criação da tabela.
	 */
	public abstract function createTable(EntityData $entityData);
	
	/**
	 * Verifica se uma tabela existe no banco de dados.
	 * 
	 * @param string $tableName Nome da tabela em "camelCase".
	 * @return bool
	 */
	public abstract function tableExist($tableName);
}