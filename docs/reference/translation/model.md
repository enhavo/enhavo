## Model

Recursively translates nested model objects and collections. Use this for related entities
that themselves contain translatable properties, such as content blocks or tree structures.

<ReferenceTable
    type="model"
    className="Enhavo\Bundle\TranslationBundle\Translation\Type\ModelTranslationType"
    parent="Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType"
>
</ReferenceTable>

::: code-group

```php [Attribute]
#[Translate('model')]
private Collection $content;
```

```yaml [YAML]
properties:
    content:
        type: model
```

:::
