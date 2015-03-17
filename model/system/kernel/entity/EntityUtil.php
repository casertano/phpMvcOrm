<?php
/**
 * Funções úteis para tratar as entidades.
 * 
 * @author Andre
 */
class EntityUtil {
	
	/**
	 * Remove os (*) e (/) do comentário.
	 * 
	 * @param string $docComment Bloco de comentário.
	 */
	public static function cleanComment($docComment) {
		return preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
	}
}