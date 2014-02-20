<?php
/**
 * PHP library for working with URI's. Requires
 * PHP 5.3.7 or later. Replaces and extends PHP's
 * parse_url()
 * 
 * Based on P Guardiario's original work
 * 
 * Example:
 *   $my_uri = new parse_uri('http://google.com/foo');
 *   $my_uri->replace('PATH', '/bar');
 *   $my_uri->append('PATH', '/baz');
 *   $my_uri->p_str();
 *   // output: http://google.com/bar/baz
 * 
 * @author  Nicholas Jordon
 * @license http://opensource.org/licenses/MIT
 * @version 0.1.0
 * @see     http://en.wikipedia.org/wiki/URI_scheme
 */
 
/**
 * Parse URI
 * 
 * Parses the input as a URI string. On failure $error
 * is set to 1 and $error_msg is populated.
 */
class parse_uri {
	
	/*** Variables ***/
	
	/**
	 * The original input to the class constructor.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://example.com');
	 * 
	 * // output: http://example.com
	 * echo $parse_uri->input;
	 * 
	 * // These are functionally identical
	 * $parse_uri->__construct($parse_uri->input);
	 * $parse_uri->reset();</pre>
	 * 
	 * @var string
	 */
	public $input;
	
	/**
	 * The connection scheme. supports both explicit and
	 * inherted schemes.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('https://example.com');
	 * 
	 * // output: https://
	 * echo $parse_uri->scheme;</pre>
	 * Regex: <code>((http(s)?|(s)?ftp|ssh):)?\/\/</code>
	 * 
	 * @var string
	 */
	public $scheme;
	/**
	 * Alias of $scheme.
	 * @var string
	 */
	public $protocol;
	
	/**
	 * The connection scheme name. This is always either
	 * alpha characters or an emtpy string.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('https://example.com');
	 * 
	 * // output: https
	 * echo $parse_uri->scheme_name;</pre>
	 * Regex: <code>(http(s)?|(s)?ftp|ssh)</code>
	 * 
	 * @var string
	 */
	public $scheme_name;
	
	/**
	 * The username of the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('ftp://jdoe:test123@example.com');
	 * 
	 * // output: jdoe
	 * echo $parse_uri->user;</pre>
	 * 
	 * @var string
	 */
	public $user;
	/**
	 * Alias of $user.
	 * @var string
	 */
	public $username;
	
	/**
	 * The password of the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('ftp://jdoe:test123@example.com');
	 * 
	 * // output: test123
	 * echo $parse_uri->pass;</pre>
	 * 
	 * @var string
	 */
	public $pass;
	/**
	 * Alias of $pass.
	 * @var string
	 */
	public $password;
	
	/**
	 * The host of the URI. This is typically a FQDN.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('//test.example.com');
	 * 
	 * // output: test.example.com
	 * echo $parse_uri->host;</pre>
	 * Regex: <code>([a-z]([a-z0-9\-]+)?\.)+([a-z]+)$</code>
	 * 
	 * @var string
	 */
	public $host;
	/**
	 * Alias of $host.
	 * @var string
	 */
	public $fqdn;
	
	/**
	 * The port of the URI as a string.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('ssh://jdoe@example.com:700');
	 * 
	 * // output: 700
	 * echo $parse_uri->port;</pre>
	 * 
	 * @var string
	 */
	public $port;
	
	/**
	 * The authority string from the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://jdoe:test123@example.com:700/path/to/file.ext?q=1#frag');
	 * 
	 * // output: jdoe:test123@example.com:700
	 * echo $parse_uri->authority;</pre>
	 * 
	 * @var string
	 */
	public $authority;
	
	/**
	 * The path of the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://example.com:700/path/to/file.ext?q=1#frag');
	 * 
	 * // output: /path/to/file.ext
	 * echo $parse_uri->path</pre>
	 * 
	 * @var string
	 */
	public $path;
	
	/**
	 * The query string of the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('example.com:700/path/to/file.ext?q=1#frag');
	 * 
	 * // output: q=1
	 * echo $parse_uri->query</pre>
	 * 
	 * @var string
	 */
	public $query;
	
	/**
	 * The fragment of the URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('example.com:700/path/to/file.ext?q=1#frag');
	 * 
	 * // output: frag
	 * echo $parse_uri->fragment</pre>
	 * 
	 * @var string
	 */
	public $fragment;
	
	/**
	 * Indicates if there is (TRUE) or is-not (FALSE)
	 * an error present.
	 * 
	 * @var boolean
	 */
	public $error;
	
	/**
	 * The error message to display.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('not a URI');
	 * 
	 * if ($parse_uri->error) {
	 *   // output:
	 *   //   Input could not be parsed as a URI!
	 *   //   not a URI
	 *   echo $parse_uri->erro_msg.PHP_EOL;
	 *   echo $parse_uri->input;
	 * }</pre>
	 * 
	 * @var string
	 */
	public $error_msg;
	
	
	/*** Methods ***/
	
	
	/**
	 * Parses the input as a URI and populates the
	 * variables. Fails if input is not a string or
	 * if the string cannot be parsed as a URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://example.com');</pre>
	 * 
	 * @param string $input The URI to parse.
	 */
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
			$this->parse_uri($input);
		}
	}
	
	/**
	 * Parses the supplied string as a URI.
	 * 
	 * @todo   Improve & extend parse_url()
	 * @param  string $uri The string to be parsed.
	 * @return void
	 */
	private function parse_uri($uri) {
		if ($this->error) {
			return FALSE;
		}
		$t = $this;
		$parsed   = parse_url((string) $uri);
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
		
		// Generate Authority
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
		$parsed['authority'] = $authority;
		
		$values = $parsed + $defaults;
		
		$t->scheme      = $values['scheme'].'://';
		$t->scheme_name = $values['scheme'];
		$t->user        = $values['user'];
		$t->pass        = $values['pass'];
		$t->host        = $values['host'];
		$t->port        = $values['port'];
		$t->authority   = $values['authority'];
		$t->path        = $values['path'];
		$t->query       = $values['query'];
		$t->fragment    = $values['fragment'];
	}
	
	/**
	 * Returns the current URI as an associative
	 * array similar to parse_url(). However it always
	 * sets each key as an empty string by default.
	 * 
	 * Array Keys:
	 *   scheme, user, pass, host, port,
	 *   authority, path, query, fragment
	 * <br>
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://example.com');
	 * $uri_arr = $parse_uri->arr();
	 * 
	 * // output: http://
	 * echo $uri_arr['scheme'];</pre>
	 * 
	 * @return array The URI as an array.
	 */
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
	
	/**
	 * Returns the current URI as a string.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->prepend('HOST', 'www.');
	 * 
	 * // output: http://www.google.com/foo
	 * echo $parse_uri->str();</pre>
	 * 
	 * @return string The current URI.
	 */
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
			}
		}
		if (!empty($t->pass)) {
			$str .= $t->pass.'@';
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
	
	/**
	 * Prints the current URI.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->append('PATH', '/bar');
	 * 
	 * // output: http://google.com/foo/bar
	 * $parse_uri->p_str();</pre>
	 * 
	 * @return void
	 */
	public function p_str() {
		if ($this->error) {
			return FALSE;
		}
		echo $this->str();
	}
	
	/**
	 * Returns an associative array of various
	 * information about the $path.
	 * 
	 * Array Keys:
	 *   dirname, basename, extension, filename
	 * <br>
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $path_info = $parse_uri->path_info();
	 * 
	 * // output: foo
	 * echo $path_info['filename'];</pre>
	 * 
	 * @return array The $path's information
	 */
	public function path_info() {
		if ($this->error) {
			return FALSE;
		}
		return pathinfo($this->path);
	}
	
	/**
	 * Appends $str to $section. By default it tries to
	 * autocorrect some errors. Setting $disable_safety
	 * to TRUE or 1 temporarly removes this functionality.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->append('PATH', '/bar');
	 * 
	 * // output: http://google.com/foo/bar
	 * $parse_uri->p_str();</pre>
	 * 
	 * @param  string  $section        The section to append to.
	 * @param  string  $str            The string to append.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
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
		} elseif($this->safety($section, $str)) {
			$this->$section = $this->$section.$this->safety($section, $str);
		} else {
			return FALSE;
		}
		return $this->str();
	}
	
	/**
	 * Prepends $str to $section. By default it tries to
	 * autocorrect some errors. Setting $disable_safety
	 * to TRUE or 1 temporarly removes this functionality.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->prepend('HOST', 'www.');
	 * 
	 * // output: http://www.google.com/foo
	 * $parse_uri->p_str();</pre>
	 * 
	 * @param  string  $section        The section to prepend to.
	 * @param  string  $str            The string to prepend.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
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
		} elseif($this->safety($section, $str)) {
			$this->$section = $this->safety($section, $str).$this->$section;
		} else {
			return FALSE;
		}
		return $this->str();
	}
	
	/**
	 * Replaces $section with $str. By default it tries
	 * to autocorrect some errors. Setting
	 * $disable_safety to TRUE or 1 temporarly removes
	 * this functionality.
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->replace('SCHEME', 'https');
	 * 
	 * // output: https://google.com/foo
	 * $parse_uri->p_str();</pre>
	 * 
	 * @param  string  $section        The section to replace.
	 * @param  string  $str            The string to replace $section with.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
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
		} elseif($this->safety($section, $str)) {
			$this->$section = $this->safety($section, $str);
		} else {
			return FALSE;
		}
		return $this->str();
	}
	
	/**
	 * Attempts to correct any errors in $str based on
	 * what $type is.
	 * 
	 * @todo   Make this work.
	 * @param  string $type The type error correction to apply.
	 * @param  string $str  The string to attempt to correct.
	 * @return mixed        The resulting string, or FALSE on failure.
	 */
	private function safety($type, $str) {
		$type = strtoupper((string) $type);
		$str = trim($str);
		$err = 0;
		switch ($type) {
			case 'SCHEME_NAME':
				if (strpos('//', $str) === FALSE) {
					$str = $str.'://';
				}
				
			case 'SCHEME':
				$str = strtolower((string) $str);
				if (!stripos($str, '://') === FALSE) { // <scheme>://
					if (!preg_match('/\A[a-z]{1,5}:\/\/(\/)?\Z/', $str)) {
						$err++;
					}
				} elseif(stripos($str, '//') === FALSE) { // inherit
					if ($str != '//') {
						$err++;
					}
				} else { // no scheme
					if (!empty($str)) {
						$err++;
					}
				}
				break;
				
			case 'USER':
				if (!preg_match('/\A[a-z0-9\-_]*\Z/i', $str)) {
					$err++;
				}
				break;
			
			case 'PASS':
				if (!preg_match('/\A[a-z0-9\-_]*\Z/i', $str)) {
					$err++;
				}
				break;
			
			case 'HOST':
				$str = strtolower((string) $str);
				if (
					!preg_match('/\A(([a-z0-9_]([a-z0-9\-_]+)?)\.)+[a-z0-9]([a-z0-9\-]+)?\Z/', $str) // fqdn
					&&
					!preg_match('/\A([0-9]\.){3}[0-9]\Z/', $str) // ip
				) {
					$err++;
				}
				break;
			
			case 'PORT':
				if (!preg_match('/\A[0-9]{0,6}\Z/', $str)) {
					$err++;
				}
				break;
			
			case 'PATH':
				if ($str[0] != '/') {
					$str = '\\'.$str;
				}
				$str = str_replace(array('\\\\', '/'), array('', '/'), $str);
				if (!preg_match('/\A(\/[a-z0-9\-_\.])+(\/)?\Z/i', $str)) {
					$err++;
				}
				break;
			
			case 'QUERY':
				// most characters are valid,
				break;
			
			case 'FRAGMENT':
				if ($str[0] == '#') {
					unset($str[0]);
				}
				if (!preg_match('/\A[a-z0-9\-]*\Z/i', $str)) {
					$err++;
				}
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
	
	/**
	 * Re-initializes the class with the original URI
	 * 
	 * Example:
	 * <pre>$parse_uri = new parse_uri('http://google.com/foo');
	 * $parse_uri->prepend('PATH', '/baz');
	 * $parse_uri->replace('SCHEME', 'https');
	 * 
	 * // output: https://google.com/baz/foo
	 * $parse_uri->p_str();
	 * 
	 * $parse_uri->reset();
	 * 
	 * // output: http://google.com/foo
	 * $parse_uri->p_str();</pre>
	 * 
	 * @return void
	 */
	public function reset() {
		$this->__construct($this->input);
	}
}
