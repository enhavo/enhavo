## Create index type

```php
class TextIndexType extends AbstractIndexType implements IndexTypeInterface
{
    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void
    {
        $text = $model->getText();
        $index = new IndexData($text, 20);
        $builder->addIndex($index);

    }
}
```
