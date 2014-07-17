<?php

require_once __DIR__.'/uri.class.php';

$uri2 = new uri('https://user:pass@example.com:777/path/to/script.php?query=str#fragment');

print_r($uri2->path_info());

