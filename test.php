<?php
/**
 * Evaluate the script functions as expected
 */

require __DIR__.'./uri.doc.php';

$uri = new uri('http://example.com/path/to/file.ext');

$uri->replace('QUERY', array('rand', (string) rand(1, 10)));
$uri->replace('PATH', '/foo/bar');
$uri->append('PATH', '.baz');
$new = $uri->prepend('HOST', 'www.');

$uri->reset();
$original = $uri->str();

$uri->replace('FRAGMENT', 'Checkout');
$secure = $uri->replace('SCHEME', 'https');

echo $new.PHP_EOL;
echo $original.PHP_EOL;
echo $secure.PHP_EOL;

?>