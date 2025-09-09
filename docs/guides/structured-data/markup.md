## Markup

Let's have a look at some markup examples.

### Mapping

The easiest case is just to map properties. You can use the `StructuredData` Attribute or yaml.
**You have to apply a type to the class first**. This tells the manager where a type begins.

Then you mark up the properties or functions and use the `property` option to map it to the type.


::: code-group

```php [Attribute]
use Enhavo\Bundle\ContentBundle\Attribute\StructuredData; // [!code ++]

#[StructuredData('blog_posting')] // [!code ++]
class Article 
{
    #[StructuredData('blog_posting', ['property' => 'headline'])] // [!code ++]
    public ?string $title;
    // ...
}
```

```yaml [Attribute]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                blog_posting:
                    type: blog_posting
            properties:
                title:
                    blog_posting:
                        type: blog_posting
                        property: headline
```
:::

Structured Data output:


```php
[
    "@context": "http://schema.org"
    "@type" => "BlogPosting",
    "headline" => "Lorem ipsum",
]
```

### Transform

Some Objects need to transformed into plain string. So you can apply a transformer. Let's apply a `media_url` transformer on the image
property to convert it into an absolute url. But we don't want a format and not the original file, so we need to pass some 
options to the transformer as well.

::: code-group

```php [Attribute]
use Enhavo\Bundle\ContentBundle\Attribute\StructuredData; // [!code ++]

#[StructuredData('blog_posting')] // [!code ++]
class Article 
{
    #[StructuredData('blog_posting', [ // [!code ++]
        'property' => 'headline', // [!code ++]
        'transform' => 'media_url', // [!code ++]
        'transform_options' => [ 'format' => 'article_wide' ] // [!code ++]
    ])] // [!code ++]
    public FileInterface $image;
    // ...
}
```



```yaml [Attribute]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                blog_posting:
                    type: blog_posting
            properties:
                image:
                    blog_posting:
                        type: blog_posting
                        property: image
                        transform: media_url
                        transform_options:
                            format: article_wide
```

:::

Structured Data output:

```php
[
        "@context" => "http://schema.org"
        "@type" => "BlogPosting",
        "image" => "https://domain.tld/media/format/article_wide/0f2396b/image.png",
]
```

### Nested mapping

In schema.org you can nest types. Here is a simple example on how you can add an author to a BlogPosting type 

::: code-group

```php [Attribute]
use Enhavo\Bundle\ContentBundle\Attribute\StructuredData; // [!code ++]

#[StructuredData('blog_posting')] // [!code ++]
class Article 
{
    #[StructuredData('model')] // [!code ++]
    public Author $image;
    // ...
}

// Author model:

#[StructuredData('author', [ // [!code ++]
    'append_type' => 'BlogPosting',  // [!code ++]
    'append_property' => 'author' // [!code ++]
])], // [!code ++]
class Author 
{
    #[StructuredData('author', ['property' => 'name'])] // [!code ++]
    public function getName() { // [!code ++]
        return $this->firstName.' '.$this->lastName; // [!code ++]
    } // [!code ++]
}
```



```yaml [YAML]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                blog_posting:
                    type: blog_posting
            properties:
                author:
                    model:
                        type: model
        App\Entity\Author:
            class:
                author:
                    type: author
                    append_type: BlogPosting
                    append_property: author
            methods:
                name:
                    author:
                        type: author
                        property: name
```

:::

Structured Data output:

```php
[
    "@context": "http://schema.org"
    "@type" => "BlogPosting",
    "author" => [
        "@type" => "Author"
        "name" => "Peter Pan"
    ],
]
```

### Nested root types

Some types can be found inside a model, but are not nested to the origin type. If you want to make it as a root type
you can check out following example.

::: code-group

```php [Attribute]
use Enhavo\Bundle\ContentBundle\Attribute\StructuredData; // [!code ++]

#[StructuredData('blog_posting')] // [!code ++]
class Article 
{
    #[StructuredData('model')] // [!code ++]
    public array $blocks = [];
    // ...
}

// ImageBlock model:

#[StructuredData('image')] // [!code ++]
class ImageBlock 
{
    #[StructuredData('image', [ // [!code ++]
        'property' => 'image', // [!code ++]
        'transform' => 'media_url', // [!code ++]
    ])] // [!code ++]
    public FileInterface $image;
}
```

```yaml [YAML]
enhavo_content:
    structured_data:
        App\Entity\Article:
            class:
                blog_posting:
                    type: blog_posting
            properties:
                author:
                    model:
                        type: model
        App\Entity\ImageBlock:
            class:
                image:
                    type: image
            property:
                image:
                    image:
                        type: image
                        property: url
                        transform: media_url

```

:::

Structured Data output:

```php
[
    "@context": "http://schema.org"
    "@type" => "BlogPosting",
    // ...
],
[
    "@context": "http://schema.org"
    "@type" => "Image",
    "url" => "https://domain.tld/media/file/0f2396b/image.png"
]
```
