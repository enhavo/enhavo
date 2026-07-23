## Text

Translates simple text or string properties. This is the most common type.

<ReferenceTable
    type="text"
    className="Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType"
    parent="Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType"
>
<template v-slot:options>
    <ReferenceOption name="html" :required="false" />, 
    <ReferenceOption name="allow_fallback" :required="false" />, 
    <ReferenceOption name="allow_auto_translate" :required="false" />, 
    <ReferenceOption name="overwrite" :required="false" />, 
    <ReferenceOption name="context_groups" :required="false" />
</template>
</ReferenceTable>

::: code-group

```php [Attribute]
#[Translate('text')]
private ?string $title = null;

#[Translate('text', ['html' => true, 'allow_fallback' => true])]
private ?string $description = null;
```

```yaml [YAML]
properties:
    title:
        type: text
    description:
        type: text
        html: true
        allow_fallback: true
```

:::

### allow_fallback

**type**: `boolean` **default**: `false`

Whether to fall back to the default locale if no translation exists.

### allow_auto_translate

**type**: `boolean` **default**: `true`

Only if `true`, the auto-translation will be applied to this property.

### html

**type**: `boolean` **default**: `false`

Whether the content should be treated as HTML. This is useful for auto translation.

### overwrite

**type**: `boolean` **default**: `false`

Set to `true` to overwrite existing translations during auto-translation.

### context_groups

**type**: `array` **default**: `['endpoint', 'translation_context']`

Use a serialization group to generate an output that is used as a context for auto translation.
