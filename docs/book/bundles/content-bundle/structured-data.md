## Structured data

Structured data refers to information organized in a predefined format, making it easier for both computers and humans to store,
search, and analyze. The structured data subsystem supports representing this information using the [Schema.org](https://schema.org) standard.

With the `StructuredDataManager` and the `getData` function, you can get a normalized array in a json-ld schema.org format, that can 
easily passed to the frontend.

```php
$article = $this->repository->find(1);
$structuredData = $this->structuredDataManager->getData($article);
```

The `$structuredData` array could look like this

```php
[
    "@context": "http://schema.org"
    "@type" => "BlogPosting",
    "headline" => "Lorem ipsum",
    "datePublished" => "2025-01-01",
    "description" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
]
```

To apply structured data, you have to use the `StructuredData` Attribute or use yaml to mark up your model. 


::: code-group

```php [Attribute]
namespace App\Entity;

use Enhavo\Bundle\ContentBundle\Attribute\StructuredData;

 #[StructuredData('blog_posting')]
class Article 
{
    #[StructuredData('blog_posting', ['property' => 'headline'])]
    private ?string $title;
    
    #[StructuredData('blog_posting', ['property' => 'description'])]
    private ?string $teaser;
}
```

```yaml [YAML]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                -
                    type: blog_posting
            properties:
                -
                    blog_posting:
                        type: blog_posting
                        property: headline
                -
                    blog_posting:
                        type: blog_posting
                        property: teaser
```

:::

To convert objects into plain text or modify your data, you can use a transformer.

::: code-group

```php [Attribute]
#[StructuredData('blog_posting', [
    'property' => 'image',
    'transform' => 'media_url'
])]
private FileInterface $image;
```

```yaml [YAML]
properties:
    image:
        blog_posting:
            type: blog_posting
            property: image
            transform: media_url
:::

Find more examples in the [Structured Data guide](/guides/structured-data/index) or check the references:
* [Structured Data reference](/reference/structured-data/index)
* [Structured Data transformer reference](/reference/structured-data-transformer/index)