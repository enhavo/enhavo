# CleverReach REST API v3 client

This library makes it easy to interact with the CleverReach REST API v3.

## Usage

**General initialization**

```php
use rdoepner\CleverReach\ApiManager;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

// Create the http client adapter
$httpAdapter = new HttpAdapter(
    [
        'credentials' => [
            'client_id' => '<CLIENT_ID>',
            'client_secret' => '<CLIENT_SECRET>',
        ],
        'access_token' => '<ACCESS_TOKEN>',
    ]
);

// Create the API manager
$apiManager = new ApiManager($httpAdapter);
```

**Get an access token**

```php
$response = $apiManager->getAccessToken();

if (isset($response['access_token'])) {
    // This access token can be used during api manager initialization
}
```

**Create a subscriber**

```php
$response = $apiManager->createSubscriber(
    '<EMAIL>',
    '<GROUP_ID>',
    true,
    [
        'salutation' => 'Mr.',
        'firstname' => 'John',
        'lastname' => 'Doe',
    ]
);

if (isset($response['id'])) {
    // ...
}
```

**Get a subscriber**

```php
$response = $apiManager->getSubscriber('<EMAIL>', '<GROUP_ID>');

if (isset($response['id'])) {
    // ...
}
```

**Delete a subscriber**

```php
$response = $apiManager->deleteSubscriber('<EMAIL>', '<GROUP_ID>');

if ($response) {
    // ...
}
```
