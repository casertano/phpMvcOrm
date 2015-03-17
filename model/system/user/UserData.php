<?php 
/**
 * @table
 * @author Andre
 */
class UserData {
	
	/**
	 * Constante para identificação do usuário do tipo administrador
	 */
	const ADMIN = 'A';
	
	/**
	 * Constante para identificação do usuário do tipo supervidor
	 */
	const SUPER = 'S';
	
	/**
	 * Constante para identificação do usuário simples
	 */
	const USER = 'U';
	
	/**
	 * Código
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idUser = 0;
	
	/**
	 * Nome do usuário.
	 * @column(type="char(25)"; notNull="true")
	 * @var string
	 */
	public $userName = '';
	
	/**
	 * Tipo do usuário.
	 * @column(type="enum('A', 'S', 'U')"; notNull="true")
	 *
	 * @var string
	 */
	public $userType = UserData::USER;
	
	/**
	 * Código da empresa.
	 * @column(type="int"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idCompany = 0;
	
	/**
	 * Situação do usuário.
	 * @column(type="enum('Y', 'N')"; notNull="true"; default="Y")
	 * @var string
	 */
	public $enabled = 'N';
}
?>