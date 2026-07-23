## Serialization

On normalization and serialization with the Symfony serializer component, the translation data will automatically be applied.
This means the output of the serializer is a fully translated object.

```php
echo $article->title;
$normalizedData = $normalizer->normalize($article, [], [])
echo "\n";
echo $normalizedData['title'];
```

The output could look like this:

```
This is a test article
Das ist ein Test Artikel
```

### Access control

The translation is only applied during serialization if the current route matches the access control configuration.
You can use `access_control` to restrict or allow specific routes using regex patterns. 
This is equivalent to the access control described in the form section above.

```yaml
# config/packages/enhavo_translation.yaml
enhavo_translation:
    translator:
        default_access: true
        access_control:
            - '/^\/api\//'
```
