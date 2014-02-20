# Magento Client Library For PHP

[![Build Status](https://travis-ci.org/cpliakas/magento-client-php.png)](https://travis-ci.org/cpliakas/magento-client-php)
[![Coverage Status](https://coveralls.io/repos/cpliakas/magento-client-php/badge.png?branch=master)](https://coveralls.io/r/cpliakas/magento-client-php?branch=master)
[![Total Downloads](https://poser.pugx.org/cpliakas/magento-client-php/downloads.png)](https://packagist.org/packages/cpliakas/magento-client-php)
[![Latest Stable Version](https://poser.pugx.org/cpliakas/magento-client-php/v/stable.png)](https://packagist.org/packages/cpliakas/magento-client-php)

Provides a client library to make REST and XMLRPC calls to a Magento instance.

## Installation

Magento Client Library For PHP can be installed with [Composer](http://getcomposer.org)
by adding it as a dependency to your project's composer.json file.

```json
{
    "require": {
        "cpliakas/magento-client-php": "*"
    }
}
```

After running `php composer.phar update` on the command line, include the
autoloader in your PHP scripts so that the SDK classes are made available.

```php
require_once 'vendor/autoload.php';
```

Please refer to [Composer's documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction)
for more detailed installation and usage instructions.

## Usage

### XMLRPC

The following example returns a list of products with SKUs that start with "123":

```php

use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

$client = MagentoXmlrpcClient::factory(array(
    'base_url' => 'http://magentohost',
    'api_user' => 'api.user',
    'api_key'  => 'some.private.key',
));

$filters = array(
    'sku' => array('like' => '123%'),
);

$result = $client->call('catalog_product.list', array($filters));
```

### Rest

The following example returns a list of products:

```php

use Magento\Client\Rest\MagentoRestClient;

$client = MagentoRestClient::factory(array(
    'base_url'        => 'http://magentohost',
    'consumer_key'    => 'abc123...',
    'consumer_secret' => 'def456...',
    'token'           => 'ghi789...',
    'token_secret'    => 'jkl012...',
));

$result = $client->get('/api/rest/products')->send()->json();

```

Refer to [Guzzle's documentation](https://guzzle.readthedocs.org/en/latest/http-client/request.html#creating-requests-with-a-client)
for more information on sending requests to the server.
