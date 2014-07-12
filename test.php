<?php

/**
 * @requires PHP 5.3.7
 * @requires PHPUnit 4.1
 */
class URITest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		$this->assertFileExists(__DIR__.'/uri.class.php');
		require_once __DIR__.'/uri.class.php';
	}
	
	public function testMinimalParsing() {
		$uri1 = new uri('example.com');
		
		// Is the RegExp valid?
		$this->assertSame(0, preg_match(uri::PARSER_REGEX, null), 'Parser RegExp is invalid');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Parsing
		$this->assertEquals('example.com', $uri1->host);
	}
	
	/**
	 * @depends testMinimalParsing
	 */
	public function testSimpleParsing() {
		$uri1 = new uri('http://example.com/sample');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Parsing
		$this->assertEquals('http://', $uri1->scheme);
		$this->assertEquals('http', $uri1->scheme_name);
		$this->assertEquals('example.com', $uri1->host);
		$this->assertEquals('/sample', $uri1->path);
	}
	
	/**
	 * @depends testSimpleParsing
	 */
	public function testAdvancedParsing() {
		$uri1 = new uri('https://example.com:777/path/to/script.php?query=str#fragment');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Parsing
		$this->assertEquals('https://', $uri1->scheme);
		$this->assertEquals('https', $uri1->scheme_name);
		$this->assertEquals('://', $uri1->scheme_symbols);
		$this->assertEquals('example.com', $uri1->host);
		$this->assertEquals('777', $uri1->port);
		$this->assertEquals('/path/to/script.php', $uri1->path);
		$this->assertEquals('query=str', $uri1->query);
		$this->assertEquals('fragment', $uri1->fragment);
	}
	
	/**
	 * @depends testAdvancedParsing
	 */
	public function testSimpleOutput() {
		$input = 'https://example.com:777/path/to/script.php?query=str#fragment';
		$uri1 = new uri($input);
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Output
		$this->assertEquals($input, $uri1->input);
		$this->assertEquals($input, $uri1->str());
		$this->expectOutputString($input);
		$uri1->p_str();
	}
	
	/**
	 * @depends testAdvancedParsing
	 */
	public function testSimpleReplace() {
		$uri1 = new uri('example.com/original/path');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Replace
		$this->assertEquals('example.com/alternative/path', $uri1->replace('PATH', '/alternative/path'));
	}
	
	/**
	 * @depends testAdvancedParsing
	 * @depends testSimpleReplace
	 * @depends testSimpleOutput
	 */
	public function testReset() {
		$uri1 = new uri('example.com/original/path');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		
		// Check Replace
		$this->assertEquals('example.com/alternative/path', $uri1->replace('PATH', '/alternative/path'));
		$uri1->reset();
		$this->assertEquals('example.com/original/path', $uri1->str());
	}
	
	/**
	 * @depends testReset
	 */
	public function testReplace() {
		$uri1 = new uri('example.com');
		$uri2 = new uri('https://example.com:777/path/to/script.php?query=str#fragment');
		
		// Check For Errors
		$this->assertEmpty($uri1->error);
		$this->assertEmpty($uri2->error);
		
		// Check Replace
		/*** Scheme Name ***/
		$this->assertEquals(
			'https://example.com',
			$uri1->replace('SCHEME_NAME', 'https')
		);
		$this->assertEquals(
			'http://example.com:777/path/to/script.php?query=str#fragment',
			$uri2->replace('SCHEME_NAME', 'http')
		);
		$uri1->reset();
		$uri2->reset();
		/*** Scheme Symbols ***/
		$this->assertEquals(
			'//example.com',
			$uri1->replace('SCHEME_SYMBOLS', '//')
		);
		$this->assertEquals(
			'https:example.com:777/path/to/script.php?query=str#fragment',
			$uri2->replace('SCHEME_SYMBOLS', ':')
		);
		$uri1->reset();
		$uri2->reset();
	}
	
	
	
}


