![alt text](enhavo.svg "enhavo")
<br/>
<br/>


# CleverReach REST API v3 client

This library makes it easy to interact with the CleverReach REST API v3.

## Installation

```bash
composer require enhavo/cleverreach
```

## Usage

**Initialize an API manager**

```php
use Enhavo\Component\CleverReach\ApiManager;
use Enhavo\Component\CleverReach\Http\SymfonyAdapter;

$adapter = new SymfonyAdapter();

// Authorize your app by credentials
$adapter->authorize('<CLIENT_ID>', '<CLIENT_SECRET>');

// Create the API manager
$apiManager = new ApiManager($adapter);
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
