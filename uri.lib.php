<?php
/**
 * PHP library for working with URI's. Requires
 * PHP 5.3.7 or later. Replaces and extends PHP's
 * parse_url()
 * 
 * Based on P Guardiario's original work
 * 
 * @author    Nicholas Jordon
 * @copyright 2014 Nicholas Jordon - All Rights Reserved
 * @license   http://opensource.org/licenses/MIT
 * @version   0.1.0
 */
 
/**
 * PHP URI
 */
class uri {
	
	/*** Variables ***/
	public $input;
	public $scheme;
	public $protocol;
	public $scheme_name;
	public $user;
	public $username;
	public $pass;
	public $password;
	public $host;
	public $fqdn;
	public $port;
	public $authority;
	public $path;
	public $query;
	public $fragment;
	public $error;
	public $error_msg;
	
	/*** Methods ***/
	
	public function __construct($input) {
		$t = $this;
		$t->input    = $input;
		$t->error    = FALSE;
		$t->protocol = &$this->scheme;
		$t->username = &$this->user;
		$t->password = &$this->pass;
		$t->fqdn     = &$this->host;
		if (!is_string($input)) {
			$t->error = TRUE;
			$t->error_msg = 'Input was not a string!';
			
			$t->scheme      = FALSE;
			$t->scheme_name = FALSE;
			$t->user        = FALSE;
			$t->pass        = FALSE;
			$t->host        = FALSE;
			$t->port        = FALSE;
			$t->authority   = FALSE;
			$t->path        = FALSE;
			$t->query       = FALSE;
			$t->fragment    = FALSE;
		} else {
			$this->parse($input);
		}
	}
	
	protected function parse($uri) {
		if ($this->error) {
			return FALSE;
		}
		$t = $this;
		$parsed = $t->_parse((string) $uri);
		if (empty($parsed)) {
			$t->error = TRUE;
			$t->error = 'Could not parse the input as a URI';
			return $parsed;
		}
		$defaults = array(
			'scheme'      => '',
			'scheme_name' => '',
			'user'        => '',
			'pass'        => '',
			'host'        => '',
			'port'        => '',
			'authority'   => '',
			'path'        => '',
			'query'       => '',
			'fragment'    => ''
		);
		
		$values = $parsed + $defaults;
		
		if (!empty($values['scheme'])) {
			$t->scheme = $values['scheme'].'://';
		} else {
			$t->scheme = '';
		}
		$t->scheme_name = $values['scheme'];
		$t->user        = $values['user'];
		$t->pass        = $values['pass'];
		$t->host        = $values['host'];
		$t->port        = $values['port'];
		$t->path        = $values['path'];
		$t->query       = $values['query'];
		$t->fragment    = $values['fragment'];
		
		$t->gen_authority();
	}
	
	private function _parse($uri) {
		$uri = (string) $uri;
		if (!version_compare(PHP_VERSION, '5.4.7') >= 0) {
			if ($uri[0] == '/') {
				unset($uri[0]);
			}
			if ($uri[0] == '/') {
				unset($uri[0]);
			}
		}
		return parse_url((string) $uri);
	}
	
	private function gen_authority() {
		$t = $this;
		$authority = '';
		
		if (!empty($t->user)) {
			$authority .= $t->user;
			if (empty($t->pass)) {
				$authority .= '@';
			} else {
				$authority .= ':';
			}
		}
		if (!empty($t->pass)) {
			$authority .= $t->pass.'@';
		}
		if (!empty($t->host)) {
			$authority .= $t->host;
		}
		if (!empty($t->port)) {
			$authority .= ':'.$t->port;
		}
		$t->authority = $authority;
	}
	
	public function arr() {
		if ($this->error) {
			return FALSE;
		}
		return array(
			'scheme'    => $this->scheme,
			'user'      => $this->user,
			'pass'      => $this->pass,
			'host'      => $this->host,
			'port'      => $this->port,
			'authority' => $this->authority,
			'path'      => $this->path,
			'query'     => $this->query,
			'fragment'  => $this->fragment
		);
	}
	
	public function str() {
		if ($this->error) {
			return FALSE;
		}
		$t = $this;
		$str = '';
		if (!empty($t->scheme)) {
			$str .= $t->scheme;
		}
		if (!empty($t->user)) {
			$str .= $t->user;
			if (empty($t->pass)) {
				$str .= '@';
			} else {
				$str .= ':';
				$str .= $t->pass.'@';
			}
		}
		if (!empty($t->host)) {
			$str .= $t->host;
		}
		if (!empty($t->port)) {
			$str .= ':'.$t->port;
		}
		if (!empty($t->path)) {
			$str .= $t->path;
		}
		if (!empty($t->query)) {
			$str .= '?'.$t->query;
		}
		if (!empty($t->fragment)) {
			$str .= '#'.$t->fragment;
		}
		return $str;
	}
	
	public function p_str() {
		if ($this->error) {
			return FALSE;
		}
		echo $this->str();
	}
	
	public function path_info() {
		if ($this->error) {
			return FALSE;
		}
		$info = pathinfo($this->path);
		
		$arr = explode('/',$this->path);
		$last = count($arr) - 1;
			
		if ($arr[$last] == '') {
			unset($arr[$last]);
		}
		if ($arr[0] == '') {
			array_shift($arr);
		}
		$info['array'] = $arr;
		
		return $info;
	}
	
	public function query_arr() {
		if ($this->error) {
			return FALSE;
		}
		parse_str($this->query, $return);
		return $return;
	}
	
	public function append($section, $str, $disable_safety = FALSE) {
		if ($this->error) {
			return FALSE;
		}
		$section = strtolower($section);
		if (!isset($this->$section)) {
			return FALSE;
		}
		if ($disable_safety) {
			$this->$section = $this->$section.$str;
		} else {
			$safety = $this->safety($section, $str);
			if ($safety != FALSE) {
				$this->$section = $this->$section.$safety;
			} else {
				return FALSE;
			}
		}
		$t->gen_authority();
		return $this->str();
	}
	
	public function prepend($section, $str, $disable_safety = FALSE) {
		if ($this->error) {
			return FALSE;
		}
		$section = strtolower($section);
		if (!isset($this->$section)) {
			return FALSE;
		}
		if ($disable_safety) {
			$this->$section = $str.$this->$section;
		} else {
			$safety = $this->safety($section, $str);
			if ($safety != FALSE) {
				$this->$section = $safety.$this->$section;
			} else {
				return FALSE;
			}
		}
		$t->gen_authority();
		return $this->str();
	}
	
	public function replace($section, $str, $disable_safety = FALSE) {
		if ($this->error) {
			return FALSE;
		}
		$section = strtolower($section);
		if (!isset($this->$section)) {
			return FALSE;
		}
		if ($disable_safety) {
			$this->$section = $str;
		} else {
			$safety = $this->safety($section, $str);
			if ($safety != FALSE) {
				$this->$section = $safety;
			} else {
				return FALSE;
			}
		}
		$t->gen_authority();
		return $this->str();
	}
	
	protected function safety($type, $str) {
		$type = strtoupper((string) $type);
		$str = trim((string) $str);
		$err = 0;
		switch ($type) {
			case 'SCHEME_NAME':
				if (!preg_match('/\A[a-z]{1,10}\Z/', $str)) {
					$err++;
				}
				break;
			
			case 'SCHEME':
				if (strpos($str, '\\') !== FALSE) {
					$str = str_replace('\\', '/', $str);
				}
				if (strpos($str, '//') === FALSE && stripos($str, ':') === FALSE) {
					if (!empty($str)) {
						$str = $str.'://'; // assume it is generic
					} else {
						break; // there is nothing to check
					}
				}
				
				$str = strtolower($str);
				if (!stripos($str, '://') === FALSE) { // explicit generic
					if (!preg_match('/\A[a-z]{1,10}:\/\/(\/)?\Z/', $str)) {
						$err++;
					}
				} elseif(stripos($str, ':') === FALSE) { // explicit pipe
					if (!preg_match('/\A[a-z]{1,10}:\Z/', $str)) {
						$err++;
					}
				} elseif(stripos($str, '//') === FALSE) { // inherit
					if ($str != '//') {
						$err++;
					}
				}
				break;
			
			case 'USER':
				$str = urlencode($str);
				break;
			
			case 'PASS':
				$str = urlencode($str);
				break;
			
			case 'HOST':
				$str = strtolower($str);
				if (
					(
						!preg_match('/\A(([a-z0-9_]([a-z0-9\-_]+)?)\.)+[a-z0-9]([a-z0-9\-]+)?\Z/', $str) // fqdn
						&&
						!preg_match('/\A([0-9]\.){3}[0-9]\Z/', $str) // ip
					)
					||
					strlen($str) > 255
				) {
					$err++;
				}
				break;
			
			case 'PORT':
				if ($str[0] == ':') {
					$str = substr($str, 1);
				}
				if (!preg_match('/\A[0-9]{0,5}\Z/', $str)) {
					$err++;
				}
				break;
			
			case 'PATH':
				$str = str_replace(array('//', '\\'), '/', $str); // common mistakes
				$path_arr = explode('/', $str);
				$safe_arr = array();
				foreach ($path_arr as $path_part) {
					$safe_arr[] = rawurlencode($path_part);
				}
				$str = implode('/', $safe_arr);
				break;
			
			case 'QUERY':
				if ($str[0] == '?') {
					$str = substr($str, 1);
				}
				$frag_loc = strpos($str, '#');
				if ($frag_loc) {
					$str = substr($str, 0, ($frag_loc - 1));
				} elseif ($str[0] == '#') {
					$str = '';
				}
				break;
			
			case 'FRAGMENT':
				if ($str[0] == '#') {
					unset($str[0]);
				}
				$str = urlencode($str);
				break;
			
			
			
			default:
				return FALSE;
				break;
		}
		
		if ($err) {
			return FALSE;
		}
		
		return $str;
	}
	
	public function reset() {
		$this->__construct($this->input);
	}
}
