# Basic sevDesk API

A simple API wrapper for sevDesk using Guzzle.


## Installation

The recommended way to install is [through composer](http://packagist.org).

    composer require iron1902/basic-sevdesk-api
    
    
## Using the api

For REST calls, the api-key is required.

```php
use Iron1902\BasicSevdeskAPI\BasicSevdeskAPI;
use Iron1902\BasicSevdeskAPI\Options;

// Create options for the API
$options = new Options();
$options->setApiKey('YOUR_API_KEY_HERE');

// Create the client
$api = new BasicSevdeskAPI($options);

//perform your requests
$result = $api->rest('GET', 'Invoice');
```
