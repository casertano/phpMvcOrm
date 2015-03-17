<?php
/**
 * Fun��es �teis para tratar as entidades.
 * 
 * @author Andre
 */
class EntityUtil {
	
	/**
	 * Remove os (*) e (/) do coment�rio.
	 * 
	 * @param string $docComment Bloco de coment�rio.
	 */
	public static function cleanComment($docComment) {
		return preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
	}
}