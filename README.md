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
        'access_token' => '<ACCESS_TOKEN>'
    ]
);

// Create the API manager
$apiManager = new ApiManager($httpAdapter);
```

**Create subscribers**

```php
// Create an activated subscriber by group, email and attributes
$response = $apiManager->createSubscriber(
    '<GROUP_ID>',
    '<EMAIL>',
    true,
    [
        'salutation' => 'Mr.',
        'firstname' => 'John',
        'lastname' => 'Doe',
    ]
);

if (isset($response['id'])) {
    // Do whatever you want here...
}
```

**Get subscribers**

```php
// Get a subscriber by group and email
$response = $apiManager->getSubscriber('<GROUP_ID>', '<EMAIL>');

if (isset($response['id'])) {
    // Do whatever you want here...
}
```

**Delete subscribers**

```php
// Delete a subscriber by group and email
$response = $apiManager->deleteSubscriber('<GROUP_ID>', '<EMAIL>');

if ($response) {
    // Do whatever you want here...
}
```
