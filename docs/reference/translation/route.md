## Route

Translates route objects.

<ReferenceTable
    type="route"
    className="Enhavo\Bundle\TranslationBundle\Translation\Type\RouteTranslationType"
    parent="Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType"
>
<template v-slot:options>
    <ReferenceOption name="allow_null" :required="false" />
</template>
</ReferenceTable>

::: code-group

```php [Attribute]
#[Translate('route')]
private ?Route $route = null;
```

```yaml [YAML]
properties:
    route:
        type: route
```

:::

### allow_null

**type**: `boolean` **default**: `false`

Whether null routes are allowed.
