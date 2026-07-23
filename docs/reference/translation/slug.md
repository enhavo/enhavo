## Slug

Extends `text` and is specialized for URL slugs. Supports all options from `text`.

<ReferenceTable
    type="slug"
    className="Enhavo\Bundle\TranslationBundle\Translation\Type\SlugTranslationType"
    parent="Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType"
>
</ReferenceTable>

::: code-group

```php [Attribute]
#[Translate('slug', ['allow_fallback' => true])]
private ?string $slug = null;
```

```yaml [YAML]
properties:
    slug:
        type: slug
        allow_fallback: true
```

:::
