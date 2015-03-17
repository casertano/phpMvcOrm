<?php
/**
 * Classe contendo m�todos �teis ao sistema.
 * 
 * @author Andr� Casertano <andre@casertano.com.br>
 */ 
class Util {

	/**
	 * Trata as requisi��es para evitar o "injection".
	 * 
	 * @param array $data Estrutura GET, POST, REQUEST, etc...
	 */
	public static function antiInjection(&$data) {
		
		// Efetua o loop sobre as vari�veis recebidas.
		foreach($data as &$sql) {
	
			// Se o valor recebido for uma array...
			if(is_array($sql)) {
				//...ent�o � efetuado a recursividade do valor.
				antiInjection($sql);
			} else {
				// Remove palavras que contenham sintaxe sql.
				$sql = preg_replace("/(from|insert|delete|drop table|show tables|#|\*|--|\\\\)/i","",$sql);
				// Limpa espa�os vazio.
				$sql = trim($sql);
				// Tira tags html e php.
				$sql = strip_tags($sql);
				// Adiciona barras invertidas a uma string.
				$sql = addslashes($sql);
			}
		}
	}
}
?>
