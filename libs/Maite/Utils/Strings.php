<?php
/**
 * Maite strings library
 *
 * This source file is subject to the "New BSD License".
 *
 * @author     Vojtěch Knyttl
 * @copyright  Copyright (c) 2010 Vojtěch Knyttl
 * @license    New BSD License
 * @link       http://knyt.tl/
 */

namespace Maite\Utils;

class Strings extends \Nette\Utils\Strings {

	/**
	 * Removes special controls characters and normalizes line endings and spaces.
	 * @param string  UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function normalize($s)
	{
		// translate HTML elements into UTF-8
		$s = html_entity_decode($s, ENT_QUOTES, 'utf-8');

		// standardize line endings to unix-like
		$s = str_replace("\r\n", "\n", $s); // DOS
		$s = strtr($s, "\r", "\n"); // Mac

		// remove control characters; leave \t + \n
		$s = preg_replace('#[\x00-\x08\x0B-\x1F\x7F]+#', '', $s);

		// merge some blank characters
		$s = str_replace(array("\xC2\xAD", "\xC2\xA0"), ' ', $s);
		$s = preg_replace("#[\t ]+#", ' ', $s);

		// trailing spaces
		$s = trim($s);

		return $s;
	}



	/**
	 * Simple text filter for handling bad user habits.
	 *
	 * @param string
	 * @return string
	 */
	public static function simpleTypography($s) {
		$s = preg_replace('/[\.]{2,}/', '…', $s);
		$s = preg_replace('/[!]{2,}/', '!', $s);
		$s = preg_replace('/[?]{2,}/', '?', $s);
		$s = preg_replace('/\s([,\.;?!…])/u', '\1', $s);
		$s = preg_replace('/([,\.;?!…])([A-Z])/u', '\1 \2', $s);
		$s = str_replace(array("´"), array("'"), $s);
		$s = self::normalize($s);
		return $s;
	}



	/**
	 * Intelligent capitalization of string.
	 *
	 * @param string
	 * @return string
	 */
	public static function intelligentCapitalize($s) {
		$s = self::capitalize($s);
		$s = preg_replace_callback('#([[:alpha:],;-–]) ([AVKSZIUO]) #u',
			function($matches) { return $matches[1].' '.mb_strtolower($matches[2], 'UTF-8').' '; }, $s);
		return $s;
	}



	/**
	 * Encoding of URL characters.
	 *
	 * @access protected
	 * @param string
	 * @return string
	 */
	public static function escapeUrl($s) {
		return
			str_replace(' ', '%20',
			str_replace('|', '%7C',
			preg_replace_callback('#[^(\x20-\x7F)]*#u', create_function('$match', 'return urlencode($match[0]);'),
			self::normalize($s))));
	}



	public static function match($re, $str, $index = false, $offset = 0) {
		preg_match($re, $str, $matches);

		if ($index !== false && !empty($matches[$index]))
			$matches = $matches[$index];

		return $matches;
	}



	public static function matchAll($re, $str, $flags = 0, $offset = 0) {
		return parent::matchAll($str, $re, $flags, $offset);
	}
}
