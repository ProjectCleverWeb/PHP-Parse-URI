<?php
/**
 * Evaluate the script functions as expected
 */

require __DIR__.'./uri.class.php';

class URITest extends PHPUnit_Framework_TestCase {
	public function testSimpleUrl() {
		$uri1 = new uri('http://example.com/sample');
		
		// Check For Errors
		$this->assertEquals(TRUE, empty($uri1->error));
		
		// Check Simple Parsing
		$this->assertEquals('example.com', $uri1->host);
		$this->assertEquals('/sample', $uri1->path);
		$this->assertEquals('http://', $uri1->scheme);
		$this->assertEquals('http', $uri1->scheme_name);
		$this->assertEquals('http://example.com/sample', $uri1->str());
	}
}


