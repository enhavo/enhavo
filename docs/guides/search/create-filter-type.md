## Create filter type

```php
class PublicFilterType extends AbstractIndexType implements IndexTypeInterface
{
    public function buildFilter(array $options, $model, string $key, FilterDataBuilder $builder): void
    {
        $public = $model->getPublic();
        $data = new FilterData($key, $public);
        $builder->addData($data);
    }

    public function buildField(array $options, string $key, FilterDataBuilder $builder): void
    {
        $builder->addField(new FilterField($key, FilterField::FIELD_TYPE_BOOLEAN));
    }
}
```
