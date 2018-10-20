# CleverReach REST API v3 client

This library makes it easy to interact with the CleverReach REST API v3.

## Usage

**Get an access token by authorizing your app**

```php
use rdoepner\CleverReach\ApiManager;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

// Create an HTTP adapter
$httpAdapter = new HttpAdapter();

// Authorize your app by credentials
$response = $httpAdapter->authorize('<CLIENT_ID>', '<CLIENT_SECRET>');

if (isset($response['access_token'])) {
    // Persist the access token for later use...
}
```

**Use the api manager**

```php
use rdoepner\CleverReach\ApiManager;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

// Create an HTTP adapter
$httpAdapter = new HttpAdapter(
    [
        'access_token' => '<ACCESS_TOKEN>',
    ]
);

// Create the API manager
$apiManager = new ApiManager($httpAdapter);
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
