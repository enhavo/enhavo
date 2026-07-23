## Metadata

To make a property translatable, you need to add metadata to your entity. That can be done with YAML or PHP attributes.
Both approaches can be combined. The YAML configuration and attributes are merged.


::: code-group

```php [Attribute]
use Enhavo\Bundle\TranslationBundle\Attribute\Translate;

class Article
{
    #[Translate('text')]
    private ?string $title = null;

    #[Translate('text', ['html' => true])]
    private ?string $teaser = null;

    #[Translate('slug', ['allow_fallback' => true])]
    private ?string $slug = null;

    #[Translate('file')]
    private ?File $picture = null;

    #[Translate('model')]
    private Collection $content;

    #[Translate('route')]
    private ?Route $route = null;
}
```

```yaml [YAML]
# config/packages/enhavo_translation.yaml
enhavo_translation:
    metadata:
        App\Entity\Article:
            properties:
                title:
                    type: text
                teaser:
                    type: text
                    html: true
                slug:
                    type: slug
                    allow_fallback: true
                picture:
                    type: file
                content:
                    type: model
                route:
                    type: route
```

:::

For a full list of available types and their options, see the [Translation Reference](/reference/translation/).
