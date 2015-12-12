[![License](https://poser.pugx.org/portatext/php-sdk/license)](https://packagist.org/packages/portatext/php-sdk)

[![Build Status](https://travis-ci.org/PortaText/php-sdk.svg)](https://travis-ci.org/PortaText/php-sdk)
[![Coverage Status](https://coveralls.io/repos/PortaText/php-sdk/badge.svg?branch=master&service=github)](https://coveralls.io/github/PortaText/php-sdk?branch=master)
[![Documentation Status](https://readthedocs.org/projects/portatext-php-sdk/badge/?version=latest)](http://portatext-php-sdk.readthedocs.org/en/latest/?badge=latest)

[![Latest Stable Version](https://poser.pugx.org/portatext/php-sdk/v/stable)](https://packagist.org/packages/portatext/php-sdk)
[![Total Downloads](https://poser.pugx.org/portatext/php-sdk/downloads)](https://packagist.org/packages/portatext/php-sdk)
[![Latest Unstable Version](https://poser.pugx.org/portatext/php-sdk/v/unstable)](https://packagist.org/packages/portatext/php-sdk)

# php-sdk
PHP Client for the [PortaText](https://www.portatext.com/) API.

# Documentation

* Autogenerated documentation for this source can be found in the [doc](https://github.com/PortaText/php-sdk/blob/master/doc/ApiIndex.md) directory.
* The [endpoint tests](https://github.com/PortaText/php-sdk/tree/master/test/endpoints) should also serve as good doucmentation on how to use the API.
* General PortaText documentation (including the REST API) can be found at the [PortaText wiki](https://github.com/PortaText/docs/wiki).

# Installing
Add this library to your [Composer](https://packagist.org/) configuration. In
composer.json:
```json
  "require": {
    "portatext": "1.*"
  }
```

# Basic use

## Getting a client instance
The first thing is to get a [Client](https://github.com/PortaText/php-sdk/blob/master/src/PortaText/Client/Base.php) instance, for example
the [Curl](https://github.com/PortaText/php-sdk/blob/master/src/PortaText/Client/Curl.php) implementation:

```php
use PortaText\Client\Curl as Client;
$portatext = new Client();
```

## (Optional) Set your logger
You can optionally set a [PSR-3](http://www.php-fig.org/psr/psr-3/) compatible logger:
```php
$portatext->setLogger($logger);
```

By default, the client will use the [NullLogger](http://www.php-fig.org/psr/psr-3/#1-4-helper-classes-and-interfaces).

## Authenticating
You can authenticate to the endpoints by using your [API key](https://github.com/PortaText/docs/wiki/REST-API#auth_api) or your username/password. This translates to
either doing this:

```php
$client->setApiKey($apiKey);
```

Or this:

```php
$client->setCredentials($username, $password);
```

When you specify a [username and password](https://github.com/PortaText/docs/wiki/REST-API#auth_basic) instead of an api key, the sdk will
automatically login and get a [session token](https://github.com/PortaText/docs/wiki/REST-API#auth_session) when needed.

# Using the endpoints
All the API commands can be found in the [Command/Api](https://github.com/PortaText/php-sdk/tree/master/src/PortaText/Command/Api)
directory. The client offers a way to instantiate them by just calling them by their name.

## Quick example
As an example, to create a template, you would do:

```php
$result = $client
  ->templates()                       // Get an instance of the Templates endpoint.
  ->text("The text of my template")
  ->description("My first template")
  ->name("template1")
  ->post();                           // Call the Templates endpoint with a POST.
```

To get a template by id:

```php
$result = $client->templates()->id(45)->get();
```

Or, to get all the templates:

```php
$result = $client->templates()->get();
```

## The result
After calling an endpoint, one of two things can happen:
 * A [PortaText Exception](https://github.com/PortaText/php-sdk/tree/master/src/PortaText/Exception) is thrown.
 * A [Result](https://github.com/PortaText/php-sdk/blob/master/src/PortaText/Command/Result.php) instance is returned.

Also, when possible, your PortaText exception will contain a `Result` object that
can be retrieved by calling `getResult()` on the exception. This is usually useful for the
[ClientError](https://github.com/PortaText/php-sdk/blob/master/src/PortaText/Exception/ClientError.php) exception, where
you will be able to see if a field was missing or different than what was expected.

### Testing for success
```php
if ($result->success) {
    ...
}
```

### Getting error strings back from the server
```php
if (!is_null($result->errors) {
    foreach ($result->errors as $error) {
        ...
  }
}
```

### Getting data back from the server
```php
if ($result->success) {
    $data = $result->data;
}
```

# Developers
This project uses [phing](https://www.phing.info/). Current tasks include:
 * test: Runs [PHPUnit](https://phpunit.de/).
 * cs: Runs [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
 * doc: Runs [PhpDocumentor](http://www.phpdoc.org/).
 * md: runs [PHPMD](http://phpmd.org/).
 * build: This is the default task, and will run all the other tasks.

## Running a phing task
To run a task, just do:

```sh
vendor/bin/phing build
```

## Contributing
To contribute:
 * Make sure you open a **concise** and **short** pull request.
 * Throw in any needed unit tests to accomodate the new code or the
 changes involved.
 * Run `phing` and make sure everything is ok before submitting the pull
 request (make phpmd and CodeSniffer happy, also make sure that phpDocumentor
 does not throw any warnings, since all our documentation is automatically
 generated).
 * Your code must comply with [PSR-2](http://www.php-fig.org/psr/psr-2/),
 CodeSniffer should take care of that.
 * If your code is accepted, it will belong to PortaText and will be published
 under the Apache2 License.

# License
The source code is released under Apache 2 License.

Check [LICENSE](https://github.com/PortaText/php-sdk/blob/master/LICENSE) file for more information.
