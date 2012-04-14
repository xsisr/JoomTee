<?php
/**
 * Web utils library.
 *
 * This source file is subject to the "New BSD License".
 *
 * @author     Vojtěch Knyttl
 * @copyright  Copyright (c) 2010 Vojtěch Knyttl
 * @license    New BSD License
 * @link       http://knyt.tl/
 */

namespace Maite\Web;

use Nette;

class Utils {

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct() {
		throw new Nette\StaticClassException;
	}



	/**
	 * Curl wrapper.
	 * @param  string
	 * @param  array
	 * @return string
	 */
	const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1)';

	public static function get($url, $params = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, @$params['user_agent'] ?: self::USER_AGENT);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "./");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

		if (!empty($params['post'])) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post']);
		}

		if (!empty($params['auth'])) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_USERPWD, $params['auth']);
		}

		$response = curl_exec( $ch );

		// if being redirected
		$info = curl_getinfo( $ch );
		curl_close( $ch );

		if ($info['http_code'] == 404)
			return false;

		if ($info['http_code'] == 301 || $info['http_code'] == 302) {
			if ($headers = get_headers($info['url'])) {
				foreach ($headers as $value) {
					if (stripos( $value, 'Location:') !== false) {
						$urln = trim(substr( $value, 9));
						if (stripos($urln, 'http://') === false) {
							preg_match('#^.*?//[^/]*#', $url, $regs);
							$urln = $regs[0].$urln;
						}
						return self::get($urln, $params);
					}
				}
			}
		}

		if (!empty($params['enc'])) {
			$response = iconv($params['enc'], 'utf-8', $response);
		}

		return $response;
	}



	/**
	 * Google search helper
	 *
	 * @param   string   needle
	 * @param   string   LocalSearch|WebSearch|VideoSearch|BlogSearch|NewsSearch|ImageSearch|PatentSearch|BookSearch
	 * @param   string   results limit
	 * @return  string
	 */
	public static function search($string, $type, $limit) {
		return json_decode(self::get(
			'http://www.google.com/uds/G' . $type .
			'Search?hl=cs&key=ABQIAAAAuzUN-vePi9PKecJElPqD6BRJSvctF1oSnOeykeMje__m2k47nBQtfuYFXNSzN15TMuSrFaxTN0faxQ&v=1.0&rsz=' .
			$limit . '&userip=' . $_SERVER['REMOTE_ADDR'] . '&q=' . urlencode($string)
		))->responseData->results;
	}
}
