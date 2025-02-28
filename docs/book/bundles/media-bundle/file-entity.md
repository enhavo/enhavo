## File entity

The `File` entity or `FileInterface` represents a single file. As it is an entity, it is easy to attach
a file to other entities. Just add a doctrine mapping.

```php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

#[ORM\Entity]
class Book
{
    #[ORM\ManyToOne(targetEntity: FileInterface::class, cascade: ['all'])]
    public FileInterface $cover;

    #[ORM\ManyToMany(targetEntity: FileInterface::class, cascade: ['all'])]
    #[ORM\JoinTable(name: 'app_book_images')]
    public Collection $images;
    
    public function __construct() {
        $this->images = new ArrayCollection();
    }
}
```

::: warning Warning
Because the owning side should always be the entity that reference the file, you can't use `oneToMany`. For multiple
files you should use `ManyToMany` instead!
:::

If a file should be uploaded by a user, you can easily use the `Media` form type.

```php
<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cover', MediaType::class);
        
        $builder->add('images', MediaType::class, [
            'multiple' => true,
        ])
    }
}
```

Or use the `FileFactory` to create a file and store it an entity. The Factory comes with a few functions to create a `File`
from different sources. If you need the `FileFactory` in a service, you can just inject it.

```php

use App\Entity\Book;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;

/** @var FileFactory $fileFactory */
/** @var FileInterface $otherFile */
/** @var ResourceManager $resourceManager */
/** @var Book $book */

// download a file
$file = $fileFactory->createFromUri('https://domain.tld/file.png');

// create from path
$file = $fileFactory->createFromPath('/path/to/file.png');

// copy from another file
$file = $fileFactory->createFromFile($otherFile);

// attach file to entity
$book->cover = $file;

// save the book
$resourceManager->save($book);

// to store only the file you can save the file
// directly with the resource manager
$resourceManager->save($file);
```
