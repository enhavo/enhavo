##  Add block to block

If you want to use a nested blocks you just need to add a property to
your parent block using the
`Enhavo\Bundle\BlockBundle\Model\NodeInterface`.

First update your meta file of your block.

```yaml
manyToOne:
    content:
        cascade: ['persist', 'refresh', 'remove']
        targetEntity: Enhavo\Bundle\BlockBundle\Model\NodeInterface
```

And add a getter and setter function to the parent block class. Set the
property and type of the node correctly. For nested blocks you set the
type to list type and add a transition to the parent node. Property
should be the member name of the parent block. This steps are important
to browse correctly through the node three later.

```php
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class MyBlock extends AbstractBlock
{
    /**
     * @param NodeInterface|null
     */
    private $content;

    public function getContent(): ?NodeInterface
    {
        return $this->content;
    }

    public function setContent(?NodeInterface $content)
    {
        $this->content = $content;
        if ($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
        }
    }
}
```

And add a migration file and execute it to update your database schema.

To show the block form, you need add the
`Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType` to your form
builder.

```php
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MyBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', BlockNodeType::class);
    }
}
```
