<?php
/**
 * Evaluate the script functions as expected
 */

require __DIR__.'./parse_uri.php';

$uri1 = new parse_uri('http://jdoe:test123@example.com:700/path/to/file.ext?q=1#frag');
$uri2 = new parse_uri('//google.com');
$uri3 = new parse_uri('example.com/foo');

print_r($uri2);

?>