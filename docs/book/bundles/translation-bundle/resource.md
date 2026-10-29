## Resource

### Duplicate

The translation bundle extends the duplicate types `property`, `string` and `file` of the
[resource bundle](/book/bundles/resource-bundle/index). If a duplicated property is translatable (see [Metadata](#metadata)),
the stored translations of all locales are copied to the duplicated resource as well. No extra markup is needed.

| Type       | Behaviour for translations                                     |
|------------|----------------------------------------------------------------|
| `property` | Copies the translations                                        |
| `string`   | Copies the translations, prefix and postfix are added to each  |
| `file`     | Duplicates the translated file of every locale                 |

Types that use one of these types as parent are extended as well.

```php
<?php
namespace App\Entity;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Bundle\TranslationBundle\Attribute\Translate;

class Article
{
    #[Translate('text')]
    #[Duplicate('string', ['postfix' => ' (Copy)', 'groups' => ['duplicate']])]
    #[Duplicate('property', ['groups' => ['revision', 'restore']])]
    private ?string $title = null;

    #[Translate('file')]
    #[Duplicate('file', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?FileInterface $picture = null;
}
```
