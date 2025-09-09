## Model

Let's assume we defined following model

```php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class Article 
{
    public ?string $title;
    public ?FileInterface $image;
    public ?Author $author;
    public array $blocks = [];
}
```

```php
namespace App\Entity;

class Author 
{
    public ?string $firstName;
    public ?string $lastName;
}
```

The property `blocks` can contain models from different block types. 

```php
namespace App\Entity;

class TextBlock 
{
    public ?string $text;
}
```

```php
namespace App\Entity;

class ImageBlock 
{
    public ?FileInterface $image;
    public ?string $caption;
}
```
