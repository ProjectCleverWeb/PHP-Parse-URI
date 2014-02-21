#PHP Parse URI

PHP library for working with URI's. Requires PHP `5.3.7` or later. Replaces and extends PHP's `parse_url()`.



Copyright &copy; 2014 Nicholas Jordon - All Rights Reserved <br>
Licensed under the MIT license

---

##Usage

####Example #1: <small>String Operations</small>

```php
<?php
$uri = new parse_uri('http://example.com/path/to/file.ext');

$uri->replace('QUERY', array('rand', (string) rand(1, 10)));
$uri->replace('PATH', '/foo/bar');
$uri->append('PATH', '.baz');
$new = $uri->prepend('HOST', 'www.');

$uri->reset();
$original = $uri->str();

$uri->replace('FRAGMENT', 'Checkout')
$secure = $uri->replace('SCHEME', 'https');

echo $new.PHP_EOL;
echo $original.PHP_EOL;
echo $secure.PHP_EOL;
```

**Output:**
```
http://www.example.com/foo/bar.baz?rand=2
http://example.com/path/to/file.ext
https://example.com/path/to/file.ext#Checkout
```


####Example #2: <small>Information Gathering</small>

```php
<?php
$uri = new parse_uri('http://example.com/path/to/file.ext?q=1');

if ($uri->scheme_name == 'https') {
	echo 'Uses SSL';
} else {
	echo 'Does not use SSL';
}

$link = sprintf('<a href="%1$s">%2$s</a>', $uri->str(), $uri->host.$uri->path);

$uri = new parse_uri('ftp://jdoe@example.com/my/home/dir');
$login = array(
	'username' => $uri->user,
	'password' => $user_input,
	'domain'   => $uri->host,
	'path'     => $uri->path
);

```

**Output:**
```
Does not use SSL
```

