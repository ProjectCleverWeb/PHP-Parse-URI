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
 * @author Nicholas Jordon
 * @license http://opensource.org/licenses/MIT
 * @version 0.1.0
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
	 * @var string
	 */
	public $input;
	
	/**
	 * The connection scheme. supports both explicit and
	 * inherted schemes.
	 * 
	 * Example: http://
	 * Example: ssh://
	 * Example: //
	 * Regex: ((http(s)?|(s)?ftp|ssh):)?\/\/
	 * 
	 * @var string
	 */
	public $scheme;
	/**
	 * Alias of $scheme.
	 * @var string
	 */
	public $protocol = &$this->scheme;
	
	/**
	 * The host of the URI. This is typically a FQDN.
	 * 
	 * Example: test123.example.com
	 * Regex: ([a-z]([a-z0-9\-]+)?\.)+([a-z]+)$
	 * 
	 * @var string
	 */
	public $host;
	/**
	 * Alias of $host.
	 * @var string
	 */
	public $fqdn = &$this->host;
	/**
	 * Alias of $host.
	 * @var string
	 */
	public $authority = &$this->host;
	
	/**
	 * The port of the URI as a string.
	 * 
	 * @var string
	 */
	public $port;
	
	/**
	 * The username of the URI.
	 * 
	 * @var string
	 */
	public $user;
	/**
	 * Alias of $user.
	 * @var string
	 */
	public $username = &$this->user;
	
	/**
	 * The password of the URI.
	 * 
	 * @var string
	 */
	public $pass;
	/**
	 * Alias of $pass.
	 * @var string
	 */
	public $password = &$this->pass;
	
	/**
	 * The path of the URI.
	 * 
	 * Example: /Path/2/fiLe123.ext
	 * Example: /file.ext
	 * Example: /
	 * 
	 * @var string
	 */
	public $path;
	
	/**
	 * The query string of the URI.
	 * 
	 * Example: ?q=foo
	 * 
	 * @var string
	 */
	public $query;
	
	/**
	 * The fragment of the URI.
	 * 
	 * Example: #foo-bar
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
	 * @var string
	 */
	public $error_msg;
	
	
	/*** Methods ***/
	
	
	/**
	 * Parses the input as a URI and populates the
	 * variables. Fails if input is not a string or
	 * if the string cannot be parsed as a URI.
	 * 
	 * @todo  replace parse_url()
	 * @param string $input The URI to parse.
	 */
	public function __construct($input) {
		$t = $this;
		$t->input = $input;
		if (!is_string($input)) {
			$t->error = TRUE;
			$t->error_msg = 'Input was not a string!';
			
			$t->scheme   = FALSE;
			$t->host     = FALSE;
			$t->port     = FALSE;
			$t->user     = FALSE;
			$t->pass     = FALSE;
			$t->path     = FALSE;
			$t->query    = FALSE;
			$t->fragment = FALSE;
		} else {
			$parsed   = parse_url($input);
			$defaults = array(
				'scheme' => '', 'host'     => '',
				'port'   => '', 'user'     => '',
				'pass'   => '', 'path'     => '',
				'query'  => '', 'fragment' => ''
			);
			
			$values = $parsed + $defaults;
			
			$t->scheme   = $values['scheme'];
			$t->host     = $values['host'];
			$t->port     = $values['port'];
			$t->user     = $values['user'];
			$t->pass     = $values['pass'];
			$t->path     = $values['path'];
			$t->query    = $values['query'];
			$t->fragment = $values['fragment'];
		}
	}
	
	/**
	 * Returns the current URI as an associative
	 * array similar to parse_url(). However it always
	 * sets each key as an empty string by default.
	 * 
	 * Array Keys:
	 *   scheme, host, port, user,
	 *   pass, path, query, fragment
	 * 
	 * @return array The URI as an array.
	 */
	public function arr() {
		return array(
			'scheme'   => $this->scheme,
			'host'     => $this->host,
			'port'     => $this->port,
			'user'     => $this->user,
			'pass'     => $this->pass,
			'path'     => $this->path,
			'query'    => $this->query,
			'fragment' => $this->fragment
		);
	}
	
	/**
	 * Returns the current URI as a string.
	 * 
	 * @return string The current URI.
	 */
	public function str() {
		$ret = "";
		if(!empty($this->scheme)) $ret .= "$this->scheme:";
		if(!empty($this->authority)) $ret .= "//$this->authority";
		$ret .= $this->normalize_path($this->path);
		if(!empty($this->query)) $ret .= "?$this->query";
		if(!empty($this->fragment)) $ret .= "#$this->fragment";
		return $ret;
	}
	
	/**
	 * Prints the current URI.
	 * 
	 * @return void
	 */
	public function p_str() {
		
	}
	
	/**
	 * Returns an associative array of various
	 * information about the $path.
	 * 
	 * Array Keys:
	 *   dirname, basename, extension, filename
	 * 
	 * @return array The $path's information
	 */
	public function path_info() {
		
	}
	
	/**
	 * Appends $str to $section. By default it tries to
	 * autocorrect some errors. Setting $disable_safety
	 * to TRUE or 1 temporarly removes this functionality.
	 * 
	 * @param  string  $section        The section to append to.
	 * @param  string  $str            The string to append.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
	public function append($section, $str, $disable_safety = FALSE) {
		$uri = new phpUri($relative);
		switch(true){
			case !empty($uri->scheme): break;
			case !empty($uri->authority): break;
			case empty($uri->path):
				$uri->path = $this->path;
				if(empty($uri->query)) $uri->query = $this->query;
			case strpos($uri->path, '/') === 0: break;
			default:
				$base_path = $this->path;
				if(strpos($base_path, '/') === false){
					$base_path = '';
				} else {
					$base_path = preg_replace ('/\/[^\/]+$/' ,'/' , $base_path);
				}
				if(empty($base_path) && empty($this->authority)) $base_path = '/';
				$uri->path = $base_path . $uri->path; 
		}
		if(empty($uri->scheme)){
			$uri->scheme = $this->scheme;
			if(empty($uri->authority)) $uri->authority = $this->authority;
		}
		return $uri->to_str();
	}
	
	/**
	 * Prepends $str to $section. By default it tries to
	 * autocorrect some errors. Setting $disable_safety
	 * to TRUE or 1 temporarly removes this functionality.
	 * 
	 * @param  string  $section        The section to prepend to.
	 * @param  string  $str            The string to prepend.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
	public function prepend($section, $str, $disable_safety = FALSE) {
		
	}
	
	/**
	 * Replaces $section with $str. By default it tries
	 * to autocorrect some errors. Setting
	 * $disable_safety to TRUE or 1 temporarly removes
	 * this functionality.
	 * 
	 * @param  string  $section        The section to replace.
	 * @param  string  $str            The string to replace $section with.
	 * @param  boolean $disable_safety The safety toggle.
	 * @return string                  The resulting URI.
	 */
	public function replace($section, $str, $disable_safety = FALSE) {
		
	}
	
	/**
	 * Re-initializes the class with the original URI
	 * 
	 * @return void
	 */
	public function reset() {
		
	}
}
?>