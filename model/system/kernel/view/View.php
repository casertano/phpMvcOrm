<?php
/**
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */
class View {
	/**
	 * Identifica a marca��o inicial de substitui��o.
	 * 
	 * @var string
	 */
	const REPLACE_TAG_LEFT = "{";
	
	/**
	 * Identifica a marca��o final de substitui��o.
	 * 
	 * @var string
	 */
	const REPLACE_TAG_RIGHT = "}";
	
	/**
	 * Identifica a exten��o do template.
	 * 
	 * @var string
	 */
	const VIEW_EXTENSION = ".html";
	
	/**
	 * Armazena o nome do controlador.
	 * 
	 * @var string
	 */
	private $controllerName;
	
	/**
	 * Armazena o nome da action.
	 * 
	 * @var string
	 */
	private $actionName;
	
	/**
	 * Armazena o nome do diret�rio.
	 * 
	 * @var string
	 */
	private $viewFolder;
	
	/**
	 * Armazena o caminho para a view.
	 * 
	 * @var string
	 */
	private $viewPath;
	
	/**
	 * Cria a p�gina de acordo com os os dados informados.
	 * 
	 * @param Object $replacement 	Dados de substitui��o.
	 * @param string $view			P�gina (template).
	 */
	public function __construct($replacement = null, ViewReplaceListTemplate $listTemplate = null, $view = null) {
		
		// Inst�ncia da classe respons�vel pelo ger�nciamento das controladoras.
		$controller = ControllerManager::getInstance();
		
		// Recupera os dados da controladora.
		$this->controllerName = $controller->getControllerName();
		$this->actionName = !empty($view) ? $view : $controller->getActionName();
		$this->viewFolder = lcfirst(str_replace(ControllerManager::SUFFIX, "", $this->controllerName));
		$this->viewPath = DIRECTORY_SEPARATOR . Application::DIR_VIEW . $controller->getControllerPath();
		
		// Se foi informada uma lista de templates...
		if(!empty($listTemplate)) {
			$this->renderList($replacement, $listTemplate);
		} else {
			// Inclui o template.
			$this->render($replacement);
		}
	}
	
	/**
	 * Prepara a exibi��o da p�gina de acordo com a estrutura mult-dimencional 
	 * informada.
	 * 
	 * @param object 			$replacement	Estrutura mult-dimencional de substitui��o.
	 * @param ViewReplaceList 	$templateList	Estrutura de templates para substitui��o. 
	 */
	private function renderList($replacement, $templateList) {
		
		// Recupera o caminho principal...
		$mainPath = Application::getPath() . $this->viewPath;
		
		// Armazena a lista de templates substituidos.
		$list = array();
		
		// Percorre a lista de substitui��o...
		foreach ($replacement as $k => $v) {
			if(is_array($v) || is_object($v)) {
				$this->replaceList($v, $templateList, $k, $mainPath, $list);
			} else {
				$list[$k] = $v;
			}
		}

		// Exibe a p�gina.
		$this->render($list);
	}
	
	/**
	 * Faz as altera��es da comprando a estrutura de dados com a lista de 
	 * templates.
	 * 
	 * @param object 			$replacement 	Estrutura de dados para substitui��o.
	 * @param ViewReplaceList 	$templateList	Estrutura de templates para substitui��o. 
	 * @param string			$templateKey	Nome da propriedade do template.
	 * @param string			$mainPath		Caminho principal para acesso aos templates.
	 * @param array 			$replaceList	Lista contendo os templates substituidos.
	 * @throws Exception
	 */
	private function replaceList($replacement, $templateList, $templateKey, $mainPath, &$replaceList = array()) {
		
		// Verifica se existe a chave correspondente na lista de templates.
		if(!array_key_exists($templateKey, $templateList))
			throw new Exception("Nao foi poss�vel identificar uma associa��o para o template '" . $templateKey . 
					"' na lista de templates.");
		
		// Caminho para o template.
		$template = $mainPath . $templateList->{$templateKey} . self::VIEW_EXTENSION;
		
		// Verifica se o "template" existe.
		if(!file_exists($template))
			throw new Exception("Nao foi poss�vel identificar o template '" 
					. $template . "' para a controladora '" . $this->controllerName . "'.");
		
		// Carrega o conte�do do arquivo.
		$file = file_get_contents($template);
		
		// Armazena as substitui��es prontas.
		$replacementReady = "";
		
		// Pecorre os dados.
		foreach ($replacement as $k => $v) {
			
			// Se for uma estrutura de objeto ou uma array associativa...
			if((is_array($v) || is_object($v)) && !is_numeric($k)) {
				$this->replaceList($v, $templateList, $k, $mainPath, $replaceList);
			// Se for uma array simples...
			} elseif ((is_array($v) || is_object($v)) && is_numeric($k)) {
				$tmp = $file;
				foreach ($v as $k1 => $v1)
					$tmp = preg_replace('/' . self::REPLACE_TAG_LEFT . trim($k1) . self::REPLACE_TAG_RIGHT . '/', $v1, $tmp);
				$replacementReady .= $tmp;
			} else {
				if(is_numeric($k)) {
					$replacementReady .= preg_replace('/' . self::REPLACE_TAG_LEFT . trim($templateKey) . self::REPLACE_TAG_RIGHT . '/', $v, $file);
				} else {	
					$replacementReady .= preg_replace('/' . self::REPLACE_TAG_LEFT . trim($k) . self::REPLACE_TAG_RIGHT . '/', $v, $file);
				}
			}
		}
		// Inclui na lista.
		$replaceList[$templateList->{$templateKey}] = $replacementReady;
	}
	
	/**
	 * Exibe a p�gina requisitada.
	 * 
	 * @param object $replacement Vetor associativo para substitui��o.
	 */
	private function render($replacement = null) {
		// Caminho para o template.
		$viewPath = Application::getPath() . $this->viewPath . $this->actionName . self::VIEW_EXTENSION;
		
		// Verifica se o "template" existe.
		if(!file_exists($viewPath))
			throw new Exception("Nao foi poss�vel identificar o template '" . 
					$viewPath . "' para a controladora '" . $this->controllerName . "'.");
		
		// Carrega o conte�do do arquivo.
		$file = file_get_contents($viewPath);
		
		// Se tem vari�vel para substitui��o...
		if(!empty($replacement))
			$this->replace($file, $replacement);
		
		// Exibe o template.
		print preg_replace("/{(.+)}/", "", $file);
	}
	
	/**
	 * Faz a substitui��o no template.
	 * � poss�vel fazer a substitui��o de vetores multi-dimencionais, mas no
	 * template � necess�rio colocar o caminho separado por '.' (ponto).
	 * Ex:
	 * $replacement = array(
	 * 		"nome" => "Jos�",
	 * 		"idade" => "33",
	 * 		"telefone" => array(
	 * 			"celular" => "99999999",
	 * 			"residencia" => "22222222"
	 * 		)
	 * )
	 * 
	 * No template:
	 * Nome: {nome}
	 * Idade: {idade}
	 * Telefone Celular: {telefone.celular}
	 * Telefone Resid�ncia:  {telefone.resid�ncia}
	 * 
	 * @param string $file Arquivo de template.
	 * @param object $replacement Dados de substitui��o.
	 * @param string $complement Complemento de substitui��o.
	 */
	private function replace(&$file, $replacement, $complement = "") {
		if(is_array($replacement) || is_object($replacement)) {
			foreach ($replacement as $k => $v) {
				if(is_array($v) || is_object($v)) {
					$this->replace($file, $v, $complement . "." . $k);
				} else {
					$file = preg_replace('/' . self::REPLACE_TAG_LEFT . $complement . trim($k) . self::REPLACE_TAG_RIGHT . '/', $v, $file);
				}
			}
		}
	}
}