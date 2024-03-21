##  Reference

### Polymorphism

Imagine you have a model class that refers to an other class by just
knowing it\'s interface and not the concrete implementation. Of course
that\'s a very common use case in object orientated programming.

Here you can see a simple page class, that contains blocks
like a text or a picture.

![image](/images/polymorph-example.png)


But if you want to translate this polymorphism into a relational
database like mysql, it\'s not so clear how your table structure will
look like.

Normally we are not interested how the sql schema will end up, because
we use doctrine as our ORM layer which should take care of it. But in
doctrine, we are not able to configure this out of the box. We need some
help by extensions. Enhavo has it\'s own extension for that use case.

### Implementations

In general, we have two obvious sql implementations with pros and cons.
Let\'s have look at the page table.

``` 
+----+---------------+------------------+
| id | text_block_id | picture_block_id |
+----+---------------+------------------+
| 1  | 1             | NULL             |
+----+---------------+------------------+
| 2  | NULL          | 1                |
+----+---------------+------------------+
```

With this structure we can use sql foreign key constraints, because one
column is a reference to one specific table. This also gives us the
option to use delete cascades. But if we add more and more blocks, the
table is growing by adding a new column for each block we add.

Further, if we have a look to the php implementation, doctrine
translates each column to a class property. As a result, to encapsulate
the data model we need to use if or switch statements for each block.

```php
class Page
{
    private $textBlock;
    private $pictureBlock;

    public function setBlock(BlockInterface $block)
    {
        if($block instanceof TextBlock) {
            $this->textBlock = $block;
        } elseif($block instanceof PictureBlock) {
            $this->pictureBlock = $block;
        }
    }
}
```

This is an anti-pattern, because it violates the open close principle
(\"Open for extensions and close for modifications\"). But we need to
edit this file every time we want to add a block.

So let\'s have a look at the other possible implementation.

``` 
+----+--------------+----------+
| id | block_class  | block_id |
+----+--------------+----------+
| 1  | TextBlock    | 1        |
+----+--------------+----------+
| 2  | PictureBlock | 1        |
+----+--------------+----------+
```

With this structure we don\'t need any further columns for blocks we
add. We just need to inform someone that a TextBlock will resolve to an
sql look up on the TextBlock table, which is a easy feature in doctrine.

On the other hand, we lose some sql features like foreign keys and thus
delete constraints. This is a risk for inconsistent data. In some cases
we can help ourselves by creating a reference from the inverse block
tables back to the page table.

And how does our php code look like? Well, it\'s strait forward. We just
implement the common polymorphism.

```php
class Page
{
    private $block;
    private $blockClass;
    private $blockId;

    public function setBlock(BlockInterface $block)
    {
        $this->block = $block;
    }
}
```

### Configuration

To make it work, the `EnhavoDoctrineExtensionBundle` will hooks into
doctrine and takes care of resolving `$blockClass` and `$blockId`, and
injects the correct block if you got the object from a repository.

For that you have to add some ORM mapping to your entity like in this
example.

```php
namespace App\Entity

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Page
{
    /** @var BlockInterface */
    private $block;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $blockClass;

    /**
     * @ORM\Column(type="integer",  nullable=true)
     */
    private $blockId;

    public function setBlock(BlockInterface $block)
    {
        $this->block = $block;
    }
}
```

Now tell the `EnhavoDoctrineExtensionBundle` for which class you want to
add the reference behavior and which field the listener has to observe.

```yaml
enhavo_doctrine_extension:
    metadata:
        App/Entity/Page:
            reference:
                block:
                    idField: blockId
                    nameField: blockClass
```

That\'s all. The `EnhavoDoctrineExtensionBundle` takes care of
`App/Entity/Page`
