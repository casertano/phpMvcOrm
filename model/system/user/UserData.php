<?php 
/**
 * @table
 * @author Andre
 */
class UserData {
	
	/**
	 * Constante para identifica��o do usu�rio do tipo administrador
	 */
	const ADMIN = 'A';
	
	/**
	 * Constante para identifica��o do usu�rio do tipo supervidor
	 */
	const SUPER = 'S';
	
	/**
	 * Constante para identifica��o do usu�rio simples
	 */
	const USER = 'U';
	
	/**
	 * C�digo
	 * @column(type="integer"; primaryKey="true"; autoIncrement="true"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idUser = 0;
	
	/**
	 * Nome do usu�rio.
	 * @column(type="char(25)"; notNull="true")
	 * @var string
	 */
	public $userName = '';
	
	/**
	 * Tipo do usu�rio.
	 * @column(type="enum('A', 'S', 'U')"; notNull="true")
	 *
	 * @var string
	 */
	public $userType = UserData::USER;
	
	/**
	 * C�digo da empresa.
	 * @column(type="int"; notNull="true"; unsigned="true")
	 * @var int
	 */
	public $idCompany = 0;
	
	/**
	 * Situa��o do usu�rio.
	 * @column(type="enum('Y', 'N')"; notNull="true"; default="Y")
	 * @var string
	 */
	public $enabled = 'N';
}
?>