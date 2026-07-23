## File

Translates file or media associations, so different files can be used per locale.

<ReferenceTable
    type="file"
    className="Enhavo\Bundle\TranslationBundle\Translation\Type\FileTranslationType"
    parent="Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType"
>
</ReferenceTable>

::: code-group

```php [Attribute]
#[Translate('file')]
private ?File $image = null;
```

```yaml [YAML]
properties:
    image:
        type: file
```

:::
