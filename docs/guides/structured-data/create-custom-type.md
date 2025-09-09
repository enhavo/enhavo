## Create custom type


```php
namespace App\StructuredData\Type;

use Enhavo\Bundle\ContentBundle\StructuredData\AbstractStructuredDataType;
use Enhavo\Bundle\ContentBundle\StructuredData\Context;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredData;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataBag;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorporationExtensionStructuredDataType extends AbstractStructuredDataType
{
    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void
    {
        $data = $bag->createType('Corporation', true);
        $data->set('url', '');
        $data->set('description', '');
    }

    public static function getName(): ?string
    {
        return 'corporation_extension';
    }
}
```


::: code-group

```php [Attribute]
namespace App\Entity;

use Enhavo\Bundle\ContentBundle\Attribute\StructuredData;

#[StructuredData('blog_posting')] 
#[StructuredData('corporation_extension')]  // [!code ++]
class Article 
{
    // ...
}
```

```yaml [YAML]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                corporation_extension:
                    type: corporation_extension
```
:::


```php
[
    "@context" => "http://schema.org"
    "@type" => "BlogPosting",
   // ...
],
[
    "@context" => "http://schema.org"
    "@type" => "Corporation",
    "url" => "",
    "description" => "",
]
```
