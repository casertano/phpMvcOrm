<?php
/**
 * Classe responsável pelo gerenciamento da aplicação.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
class Application {
	
	/**
	 * Identifica o diretório das classes modelo.
	 * 
	 * @var string
	 */
	const DIR_MODEL = "model";
	
	/**
	 * Identifica o diretório das classes de controle.
	 * 
	 * @var string
	 */
	const DIR_CONTROLLER = "controller";
	
	/**
	 * Identifica o diretório dos "templates".
	 * 
	 * @var string
	 */
	const DIR_VIEW = "view";
	
	/**
	 * Identifica o diretório de configuração.
	 *
	 * @var string
	 */
	const DIR_CONFIG = "config";
	
	/**
	 * Recupera o caminho para acesso aos diretórios do sistema.
	 * 
	 * @return string Caminho principal do sistema.
	 */
	public static function getPath() {
		return substr(dirname(__FILE__), 0, (strrpos(dirname(__FILE__), self::DIR_MODEL)));
	}
	
	/**
	 * Inicia a aplicação.
	 */
	public function start() {
		
		// Trata os "injections".
		Util::antiInjection($_GET);
		Util::antiInjection($_POST);
		Util::antiInjection($_REQUEST);
		
		try {
			// Se foi informada a classe de controle...
			if(!empty($_GET['controller'])) {
				// ... Recupera o nome da classe de controle.
				$controllerName = ControllerManager::check($_GET['controller']);
				$actionName = "";
			
				// Se foi informada a view...
				if(!empty($_GET['action']))
					$actionName = ControllerManager::checkAction($_GET['controller'], $_GET['action']);
			
				// Cria a instância da classe de controle.
				$class = null;
				eval("\$class = new \$controllerName();");
			
				// Remove os parâmetros de identificação.
				unset($_GET['controller']);
				unset($_GET['action']);
				unset($_REQUEST['controller']);
				unset($_REQUEST['action']);
			
				// Verifica se foi informada a action.
				if(!empty($actionName)) {
					eval("\$class->\$actionName();");
				} else {
					$class->index();
				}
			}
		} catch (Exception $e) {
			// TODO: Tratar Excessões..
			echo $e->getMessage();
		}
	}
}