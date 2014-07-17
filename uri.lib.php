<?php

namespace {
	
	class uri extends \uri\main {
		
		
		
		/*** Variables ***/
		public $error;
		public $input;
		
		
		/*** Magic Methods ***/
		
		
		/**
		 * Parses the input as a URI and populates the
		 * variables. Fails if input is not a string or
		 * if the string cannot be parsed as a URI.
		 * 
		 * @param string $input The URI to parse.
		 */
		public function __construct($input) {
			
		}
		
		/**
		 * If this class gets typecast as a sting it should
		 * return the current URI as a string.
		 * 
		 * @return false|string The current URI.
		 */
		public function __toString() {
			
		}
		
		/**
		 * If this object is called as a function, it will
		 * re-initialize around the new input
		 * 
		 * @param  string $input The new URI to parse
		 * @return void
		 */
		public function __invoke($input) {
			
		}
		
		
		
		/*** Methods ***/
		
		
		
		/**
		 * Parses the supplied string as a URI and sets the
		 * variables in the class.
		 * 
		 * @param  string $uri The string to be parsed.
		 * @return boolean
		 */
		private function parse($uri) {
			
		}
		
		
		/**
		 * Standard function to re-genrate $authority
		 * 
		 * @return boolean
		 */
		public function gen_authority() {
			
		}
		
		/**
		 * Returns the current URI as an associative
		 * array similar to parse_url(). However it always
		 * sets each key as an empty string by default.
		 * 
		 * @return false|array The URI as an array.
		 */
		public function arr() {
			
		}
		
		/**
		 * Alias of arr()
		 * @return false|array The URI as an array.
		 */
		public function to_array() {
			
		}
		
		/**
		 * Returns the current URI as a string.
		 * 
		 * @return false|string The current URI.
		 */
		public function str() {
			
		}
		
		/**
		 * alias of str()
		 * @return false|string The current URI.
		 */
		public function to_string() {
			
		}
		
		/**
		 * Prints the current URI.
		 * 
		 * @return boolean
		 */
		public function p_str() {
			
		}
		
		/**
		 * Returns an associative array of various
		 * information about the $path.
		 * 
		 * Array Keys:
		 *   dirname, basename, extension, filename, array
		 * 
		 * @return false|array The $path's information
		 */
		public function path_info() {
			
		}
		
		/**
		 * Returns the query string parsed into an array
		 * 
		 * @return false|null|array $query as an array
		 */
		public function query_arr() {
			
		}
		
		/**
		 * Appends $str to $section. By default it tries to
		 * autocorrect some errors.
		 * 
		 * @param  string  $section The section to append to.
		 * @param  string  $str     The string to append.
		 * @return false|string     The resulting URI.
		 */
		public function append($section, $str) {
			
		}
		
		/**
		 * Prepends $str to $section. By default it tries to
		 * autocorrect some errors.
		 * 
		 * @param  string  $section The section to prepend to.
		 * @param  string  $str     The string to prepend.
		 * @return false|string     The resulting URI.
		 */
		public function prepend($section, $str) {
			
		}
		
		/**
		 * Replaces $section with $str. By default it tries
		 * to autocorrect some errors.
		 * 
		 * @param  string  $section The section to replace.
		 * @param  string  $str     The string to replace $section with.
		 * @return false|string     The resulting URI.
		 */
		public function replace($section, $str) {
			
		}
		
		/**
		 * Re-initializes the class with the original URI
		 * 
		 * @return void
		 */
		public function reset() {
			
		}
		
		
		
		
	}
	
}

namespace uri {
	
	
	
	/**
	 * 
	 */
	class main {
		
		
		
		
		
	}
	
	
	
	/**
	 * 
	 */
	class error {
		
		
		
		
		
	}
	
	
	
	/**
	 * 
	 */
	class parse {
		
		/*** Constants ***/
		
		const PARSER_REGEX = '/^(([a-z]+)?(\:\/\/|\:|\/\/))?(?:([a-z0-9$_\.\+!\*\'\(\),;&=\-]+)(?:\:([a-z0-9$_\.\+!\*\'\(\),;&=\-]*))?@)?((?:\d{3}.\d{3}.\d{3}.\d{3})|(?:[a-z0-9\-_]+(?:\.[a-z0-9\-_]+)*))(?:\:([0-9]+))?((?:\:|\/)[a-z0-9\-_\/\.]+)?(?:\?([a-z0-9$_\.\+!\*\'\(\),;:@&=\-%]*))?(?:#([a-z0-9\-_]*))?/i';
		
		
		
	}
	
	
	
	/**
	 * 
	 */
	class modify {
		
		
		
		
		
	}
	
	
	
	/**
	 * 
	 */
	class query {
		
		
		
		
		
	}
	
}
