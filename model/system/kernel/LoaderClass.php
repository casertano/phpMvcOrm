<?php
require_once 'Application.php';

/**
 * Classe responsável pela auto-leitura(__autoload) das demais classes do sistema.
 * 
 * @author André Casertano <andre@casertano.com.br>
 */
class LoaderClass {
	
	/**
	 * Classe responsável pela auto-leitura das demais classes do sistema.
	 */
	public function LoaderClass() {
		spl_autoload_register(array($this, 'loader'));
	}
	
	/**
	 * Carrega a classe requisitada.
	 * 
	 * @param string $class Nome da classe.
	 */
	private function loader($class) {
		// Verifica se a classe é um controller...
		if(!preg_match("/Controller$/", $class)) {
			$path = Application::getPath() . Application::DIR_MODEL . DIRECTORY_SEPARATOR;
		} else {
			$path = Application::getPath() . Application::DIR_CONTROLLER . DIRECTORY_SEPARATOR;
		}
		
		// Recupera o caminho para a classe solicitada.
		$classDir = $this->searchClassDir($path, $class);
		if(empty($classDir))
			throw new Exception("Não foi possível localizar a classe '" . $class . "'.");
		
		// Faz a inclusão da classe.
		require_once $classDir;
	}
	
	/**
	 * Percorre o diretório de classes do sistema.
	 * 
	 * @param string $path Caminho para os modelos.
	 * @param string $class Nome da classe.
	 * @return sttring Caminho para acesso a classe.
	 */
	private function searchClassDir($path, $class) {
		if (is_dir($path)) {
			$recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
			foreach($recursiveIteratorIterator as $d) {
				if($d->isFile() && $d->getFileName() == $class . ".php") {
					return $d->getPath() . DIRECTORY_SEPARATOR . $d->getFileName();
				}
			}
			return null;
		}
	}
}
?>