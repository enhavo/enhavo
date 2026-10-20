## Resource

### Duplicate

The duplicate types of the [resource bundle](/book/bundles/resource-bundle/index) only copy the value of the default locale.
To copy the translations of a property as well, use the translation duplicate types. They work like their parent types
and additionally copy the stored translations of all locales to the duplicated resource.

| Type                   | Parent     | Behaviour                                                         |
|------------------------|------------|-------------------------------------------------------------------|
| `translation_property` | `property` | Copies the value and its translations                             |
| `translation_string`   | `string`   | Like `string`, prefix and postfix are added to every translation  |
| `translation_file`     | `file`     | Duplicates the file and the translated file of every locale       |

All options of the parent type are available, e.g. `prefix` and `postfix` for `translation_string`.
Translations are only copied if the property is translatable (see [Metadata](#metadata)),
otherwise the types behave like their parent types.

::: code-group

```php [Attribute]
<?php
namespace App\Entity;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Bundle\TranslationBundle\Attribute\Translate;

class Article
{
    #[Translate('text')]
    #[Duplicate('translation_string', ['postfix' => ' (Copy)', 'groups' => ['duplicate']])]
    #[Duplicate('translation_property', ['groups' => ['revision', 'restore']])]
    private ?string $title = null;

    #[Translate('text', ['html' => true])]
    #[Duplicate('translation_property', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?string $teaser = null;

    #[Translate('file')]
    #[Duplicate('translation_file', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?FileInterface $picture = null;
}
```

```yaml [YAML]
enhavo_resource:
    duplicate:
        App\Entity\Article:
            properties:
                title:
                    -
                        type: translation_string
                        postfix: ' (Copy)'
                        groups: ['duplicate']
                    -
                        type: translation_property
                        groups: ['revision', 'restore']
                teaser:
                    type: translation_property
                    groups: ['duplicate', 'revision', 'restore']
                picture:
                    type: translation_file
                    groups: ['duplicate', 'revision', 'restore']
```

:::

On the [duplicate reference section](/reference/duplicate/index), you can find the options of the types.