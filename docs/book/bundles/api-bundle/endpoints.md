## Endpoints

The Api Bundle introduce the concept of endpoints. An endpoint is a class with a `handleRequest`
function to process the request and create data. Similar to an action of a controller,
but it will not expect a response for returning. Instead we apply data and let decide later how a response
will look like.

Here we have a simple endpoint, that uses the `Route` attribute for routing and find a book by the request and add the title to the data.

```php
namespace App\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/book/{slug}', name: 'app_book', defaults: ['_format' => 'html'])]
class BookEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $book = $this->findBook($request);
        $data->set('title', $book->getTitle());
    }
    
    private function findBook(Request $request)
    {
        // ...
    }
}
```

::: info Note
The data you add, must be a scalar type or an array. If you have an object, you have to normalize it first.
:::

The Api Bundle only supports a json response by default, but could be extended in your application or use the endpoints
in the app bundle to use templates for example.


### Change status code

You can use the context object to change the response behaviour, e.g. change the status code.

```php
public function handleRequest($options, Request $request, Data $data, Context $context)
{
    $book = $this->findBook($request);
    if ($book === null) { // [!code ++]
         $context->setStatusCode(400); // [!code ++]
         return; // [!code ++]
    } // [!code ++]
    $data->set('title', $book->getTitle());
}
```

### Redirect

It is also possible to pass a hole response to the context.

```php
public function handleRequest($options, Request $request, Data $data, Context $context)
{
    $book = $this->findBook($request);
    if ($book->titleChanged()) { // [!code ++]
         $response = new RedirectResponse($this->generateUrl('app_book', [ // [!code ++]
            'id' => $book->getId(); // [!code ++]
         ])) // [!code ++]
         $context->setResponse($response); // [!code ++]
         return; // [!code ++]
    } // [!code ++]
    $data->set('title', $book->getTitle());
}
```

::: warning Warning
The response is just a suggestion for the endpoint. In some cases this might be ignored.
:::

### Load endpoints

If you use route attributes for endpoints. Make sure you load the Endpoint folder in your routing. It will also check all
subfolder for further endpoints.

```yaml
endpoint:
    resource: ../src/Endpoint
    type: endpoint
```

It is also possible to use prefixes if you load a folder.

```yaml
endpoint:
    resource: ../src/Endpoint/User
    prefix: /user
    type: endpoint
```

::: warning Warning
If you use prefixes for different folders, you should take care, that no folder will be loaded twice. The last loaded route will always
count, but routes can be only overwritten by name. So if you don't use names for the routes it will be added twice.
:::

### Routing

Instead of php attributes, you can also define routes over the configuration. That make sense if you wan't to use
different options for endpoints.

```yaml
app_book:
    path: /book/{slug}
    defaults:
        _endpoint:
            type: App\Endpoint\BookEndpointType
```

### Options

If your endpoint will be reused for different routes, you can make it configurable with options.
For the options we use the [OptionsResolver](https://symfony.com/doc/current/components/options_resolver.html) component from Symfony

Use the function `configureOptions` to define the possible options for the endpoint. The resolved options will be passed to several
functions of the endpoint e.g. `handleRequest`

```php
use Symfony\Component\OptionsResolver\OptionsResolver; // [!code ++]

class BookEndpointType extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $book = $this->findBook($request);
        if ($options['description']) { // [!code ++]
            $data->set('description', $book->getDescription()); // [!code ++]
        } // [!code ++]
        $data->set('title', $book->getTitle());
    }
    
    public function configureOptions(OptionsResolver $resolver): void // [!code ++]
    { // [!code ++]
        $resolver->setDefaults([ // [!code ++]
            'description' => true, // [!code ++]
        ]); // [!code ++]
    } // [!code ++]
    
    private function findBook(Request $request)
    {
        // ...
    }
}
```

So if you use the endpoint with the routing configuration, you are now able to change its behaviour over options.

```yaml{6,13}
app_book:
    path: /book/{slug}
    defaults:
        _endpoint:
            type: App\Endpoint\BookEndpointType
            description: true

app_other_book:
    path: /other/book/{slug}
    defaults:
        _endpoint:
            type: App\Endpoint\BookEndpointType
            description: false
```


