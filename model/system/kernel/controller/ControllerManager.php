<?php
/**
 * Classe responsável pelo gerenciamento das controladoras.
 * 
 * @author André Casertano <andre@casertano.com.br>
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
	 * Armazena o nome da página (action) solicitada.
	 *
	 * @param string $actionName Nome da página (action).
	 */
	private static function setActionName($actionName) {
		$controller = ControllerManager::getInstance();
		$controller->actionName = $actionName;
	}
	
	/**
	 * Recupera o nome da action (página).
	 *
	 * @return string Nome da página atual.
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
	 * Recupera a instância da classe.
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
	 * Verifica a existência de uma determinada classe de controle.
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
	 * Verifica se a página (view) referênte a classe de controle existe.
	 * 
	 * @param string $controllerName 	Nome da classe de controle.
	 * @param string $actionName 		Nome da página (view). 
	 * @throws Exception
	 * @return string Nome da página.
	 */
	public static function checkAction($controllerName, $actionName) {
		// Consiste os parâmetros.
		$controllerName = self::check($controllerName);
		$actionName = self::composeName($actionName, false);
		
		// Verifica se o método exite.
		if(!method_exists($controllerName, $actionName))
			throw new Exception("A página ($controllerName/$actionName) solicitada não existe.");
		
		// Armazena o nome da action.
		self::setActionName($actionName);
		
		return $actionName;
	}
	
	/**
	 * Compõe o nome da classe ou da página requisitada (action)
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
	 * Actions (páginas/views):
	 * camelCase
	 * 
	 * Onde "PascalCase" é o nome da classe, e "camelCase" nome da página
	 * interna nas views.
	 * 
	 * @param string $name 			Nome da classe de controle ou página (action).
	 * @param string $useUCFirst	Indica se a primeira letra do nome deve ser maiúscula
	 * @return string Nome formatado.
	 */
	private static function composeName($name, $useUCFirst = true) {
		// Se o nome da classe de controle tem undeline (_)...
		if(stripos($name, "_") >= 0) {
			// Separa nome...
			$name = explode("_", $name);
			// Converte todas as primeiras letras para maiúscula...
			foreach($name as &$c)
				$c = ucfirst(strtolower($c));
			
			// Re-compõe o nome.
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
		throw new Exception("Não é possível a clonagem da classe '" . __CLASS__ . "'.");
	}
}
?>