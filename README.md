# CleverReach REST API v3 client

This library makes it easy to interact with the CleverReach REST API v3.

## Usage

**Get an access token**

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

**Initialize an API manager**

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

**Create an inactive subscriber**

```php
$response = $apiManager->createSubscriber(
    '<EMAIL>',
    '<GROUP_ID>',
    false, // not activated
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

**Trigger Double-Opt-In email for an inactive subscriber**

```php
$response = $apiManager->triggerDoubleOptInEmail('<EMAIL>', '<FORM_ID>');

if (isset($response['success'])) {
    // ...
}
```

**Trigger Double-Opt-Out email for an active subscriber**

```php
$response = $apiManager->triggerDoubleOptOutEmail('<EMAIL>', '<FORM_ID>');

if (isset($response['success'])) {
    // ...
}
```

**Get subscriber**

```php
$response = $apiManager->getSubscriber('<EMAIL>', '<GROUP_ID>');

if (isset($response['id'])) {
    // ...
}
```

**Set active status of a subscriber**

```php
$response = $apiManager->getSubscriber('<EMAIL>', '<GROUP_ID>', '<TRUE_OR_FALSE>');

if (true === $response) {
    // ...
}
```

**Delete subscriber**

```php
$response = $apiManager->deleteSubscriber('<EMAIL>', '<GROUP_ID>');

if (true === $response) {
    // ...
}
```
