## Routing

The translation bundle provides routing integration to generate locale-specific URLs
for your entities. It consists of two parts: a route auto-generator that creates translated routes,
and a route strategy that resolves the correct route based on the current locale.

### Auto generator

The `locale_prefix` generator automatically creates routes with locale prefixes for each configured locale.
For example, a page with the title "About us" would get routes like `/en/about-us`, `/de/ueber-uns` and `/fr/a-propos`.

```yaml
# config/packages/enhavo_routing.yaml
enhavo_routing:
    classes:
        App\Entity\Page:
            generators:
                prefix:
                    type: locale_prefix
                    property: title
```

The `property` option defines which property is used to generate the url slug.

### Route strategy

To resolve the correct translated route when generating URLs, configure the `translation_route` strategy
for your entity.

```yaml
# config/packages/enhavo_routing.yaml
enhavo_routing:
    classes:
        App\Entity\Page:
            router:
                default:
                    type: translation_route
```

When a URL is generated for the entity, the strategy checks the current locale and returns
the matching translated route. If no translation exists for the current locale, it falls back
to the default route.

### Metadata

Make sure the `route` property is marked as translatable in the translation metadata.

::: code-group

```php [Attribute]
#[Translate('route')]
private ?Route $route = null;
```

```yaml [YAML]
enhavo_translation:
    metadata:
        App\Entity\Page:
            properties:
                route:
                    type: route
```

:::
