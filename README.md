#PHP URI

PHP library for working with URI's. Requires PHP `5.3.7` or later. Replaces and extends PHP's `parse_url()`.



Copyright &copy; 2014 Nicholas Jordon - All Rights Reserved <br>
Licensed under the MIT license

---

##Usage
As with any library you will need to include the script like so: `include_once __DIR__.'/php-uri/uri.lib.php';`<br>
The `uri.doc.php` file is meant to help your understanding of the script, and should not be used in production code. 

####Example #1: String Operations

```php
<?php
$uri = new uri('http://example.com/path/to/file.ext');

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

?>
```

**Output:**
```
http://www.example.com/foo/bar.baz?rand=2
http://example.com/path/to/file.ext
https://example.com/path/to/file.ext#Checkout
```


####Example #2: Information Gathering

```php
<?php
$uri = new uri('http://example.com/path/to/file.ext?q=1');

if ($uri->scheme_name == 'https') {
	echo 'Uses SSL';
} else {
	echo 'Does not use SSL';
}

// easier to read links
$link = sprintf('<a href="%1$s">%2$s</a>', $uri->str(), $uri->host.$uri->path);

// FTP logins
$uri = new uri('ftp://jdoe@example.com/my/home/dir');
$login = array(
	'username' => $uri->user,
	'password' => $user_input,
	'domain'   => $uri->host,
	'path'     => $uri->path
);

?>
```

**Output:**
```
Does not use SSL
```

####Example #3: Production Code

By default, the `append()`, `prepend()`, and `replace()` functions have a safety feature to help prevent errors. This feature often uses regex (slow) to validate the input, and it is recommended that you disable the safety whenever you know the input will be correctly formatted.

```php
<?php
$uri = new uri('http://example.com/path/to/file.ext?q=1');

$hardcoded  = 'msg=hello%20world';
$user_input = 'msg=I am #1';

echo $uri->prepend('QUERY',$hardcoded, 1); // OK (the space is already encoded)

$uri->reset();
$uri->prepend('QUERY',$user_input); // OK (special characters get encoded)

$uri->reset();
$uri->prepend('QUERY',$user_input, 1); // NOT OK (some browsers might encode the spaces but the "#1" will be treated as a fragment)

?>
```

**Output:**
```
Does not use SSL
```

