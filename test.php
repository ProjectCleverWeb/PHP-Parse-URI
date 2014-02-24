<?php
/**
 * Evaluate the script functions as expected
 */

require __DIR__.'./uri.lib.php';

$uri1 = new uri('http://jdoe:test123@example.com:700/path/to/file.ext?q=1#frag');
$uri2 = new uri('google.com');

$prints = array();
$full   = &$prints['full'];
$basic  = &$prints['basic'];


$full['vars'] = $uri1;
$basic['vars'] = $uri2;

$full['arr()'] = $uri1->arr();
$basic['arr()'] = $uri2->arr();

$full['str()'] = $uri1->str();
$basic['str()'] = $uri2->str();

$full['path_info()'] = $uri1->path_info();
$basic['path_info()'] = $uri2->path_info();

$full['query_arr()'] = $uri1->query_arr();
$basic['query_arr()'] = $uri2->query_arr();

var_dump($prints);

?>