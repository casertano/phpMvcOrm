<?php
/**
 * Classe respons�vel pelo gerenciamento das controladoras.
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class ControllerManager {
	
	/**
	 * Define o sufixo para as controladoras.
	 * 
	 * @var string
	 */
	const SUFFIX = "Controller";
	
	/**
	 * Armazena a instancia da classe.
	 * 
	 * @var Controller
	 */
	private static $controller;
	
	/**
	 * Armazena o nome do controlador requisitado.
	 * 
	 * @var string
	 */
	private $controllerName;
	
	/**
	 * Armazena o caminho para a controladora.
	 * 
	 * @var string
	 */
	private $controllerPath;
	
	/**
	 * Armazena o nome da action requisitada
	 * 
	 * @var string
	 */
	private $actionName;
	
	/**
	 * Armazena o nome da controladora requisitada.
	 *
	 * @param string $controllerName Nome da controladora.
	 */
	private static function setControllerName($controllerName) {
		$controller = ControllerManager::getInstance();
		$controller->controllerName = $controllerName;
	}
	
	/**
	 * Recupera o nome da controladora.
	 * 
	 * @return string Nome da controladora atual.
	 */
	public function getControllerName() {
		$controller = ControllerManager::getInstance();
		return $controller->controllerName; 
	}
	
	/**
	 * Armazena o nome da p�gina (action) solicitada.
	 *
	 * @param string $actionName Nome da p�gina (action).
	 */
	private static function setActionName($actionName) {
		$controller = ControllerManager::getInstance();
		$controller->actionName = $actionName;
	}
	
	/**
	 * Recupera o nome da action (p�gina).
	 *
	 * @return string Nome da p�gina atual.
	 */
	public function getActionName() {
		$controller = ControllerManager::getInstance();
		return $controller->actionName;
	}
	
	/**
	 * Armazena o caminho da controller.
	 *
	 * @param string $controlerName Nome da controladora.
	 */
	private static function setControllerPath($controllerName) {
		
		// Recupera os dados da classe.
		$reflectionClass = new ReflectionClass($controllerName);
		$begin = strpos($reflectionClass->getFileName(), Application::DIR_CONTROLLER)+strlen(Application::DIR_CONTROLLER);
		$end = strrpos($reflectionClass->getFileName(), $controllerName)-$begin;
		
		$controller = ControllerManager::getInstance();
		$controller->controllerPath = substr($reflectionClass->getFileName(), $begin, $end);
	}
	
	/**
	 * Recupera o caminho para a controladora.
	 * 
	 * @return string
	 */
	public function getControllerPath() {
		return $this->controllerPath;
	}
	
	/**
	 * Construtor
	 */
	private function __construct() {}
	
	/**
	 * Recupera a inst�ncia da classe.
	 * 
	 * @return Controller
	 */
	public static function getInstance() {
		if(!isset(self::$controller)) {
			$class = __CLASS__;
			self::$controller = new $class;
		}
		return self::$controller;
	}
	
	/**
	 * Verifica a exist�ncia de uma determinada classe de controle.
	 * 
	 * @param string $controllerName Nome da classe de controle.
	 * @throws Exception
	 * @return string Nome da classe de controle
	 */
	public static function check($controllerName) {
		
		$controllerName = self::composeName($controllerName);
		
		// Verifica se foi incluido o "sufixo Controller"
		if(stripos($controllerName, self::SUFFIX) === false)
			$controllerName = $controllerName . self::SUFFIX;
		
		// Armazena o nome da controladora.
		self::setControllerName($controllerName);
		
		// Armazena o caminho.
		self::setControllerPath($controllerName);
		
		return $controllerName;
	}
	
	/**
	 * Verifica se a p�gina (view) refer�nte a classe de controle existe.
	 * 
	 * @param string $controllerName 	Nome da classe de controle.
	 * @param string $actionName 		Nome da p�gina (view). 
	 * @throws Exception
	 * @return string Nome da p�gina.
	 */
	public static function checkAction($controllerName, $actionName) {
		// Consiste os par�metros.
		$controllerName = self::check($controllerName);
		$actionName = self::composeName($actionName, false);
		
		// Verifica se o m�todo exite.
		if(!method_exists($controllerName, $actionName))
			throw new Exception("A p�gina ($controllerName/$actionName) solicitada n�o existe.");
		
		// Armazena o nome da action.
		self::setActionName($actionName);
		
		return $actionName;
	}
	
	/**
	 * Comp�e o nome da classe ou da p�gina requisitada (action)
	 * de acordo com as regras do sistema.
	 * 
	 * Classes:
	 * PascalCase
	 * 
	 * Estrutura de Dados para ORM:
	 * PascalCaseData
	 * 
	 * Controladoras:
	 * PascalCaseController
	 * 
	 * Actions (p�ginas/views):
	 * camelCase
	 * 
	 * Onde "PascalCase" � o nome da classe, e "camelCase" nome da p�gina
	 * interna nas views.
	 * 
	 * @param string $name 			Nome da classe de controle ou p�gina (action).
	 * @param string $useUCFirst	Indica se a primeira letra do nome deve ser mai�scula
	 * @return string Nome formatado.
	 */
	private static function composeName($name, $useUCFirst = true) {
		// Se o nome da classe de controle tem undeline (_)...
		if(stripos($name, "_") >= 0) {
			// Separa nome...
			$name = explode("_", $name);
			// Converte todas as primeiras letras para mai�scula...
			foreach($name as &$c)
				$c = ucfirst(strtolower($c));
			
			// Re-comp�e o nome.
			$name = implode($name);
		}
		return (!$useUCFirst) ? lcfirst($name) : $name;
	}
	
	/**
	 * Evita a clonagem da classe.
	 *
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception("N�o � poss�vel a clonagem da classe '" . __CLASS__ . "'.");
	}
}
?>