## Add custom filter

To add you custom filter, just add class which extends from
`Enhavo\Bundle\AppBundle\Filter\AbstractFilter`

```php
namespace AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;use Symfony\Component\OptionsResolver\OptionsResolver;

class DateFilter extends AbstractFilter
{
    public function render($options, $name)
    {
        return $this->renderTemplate($options['template'], [
            'type' => $this->getType(),
            'label' => $options['label'],
            'translationDomain' => $options['translation_domain'],
            'name' => $name,
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value) {
            $property = $options['property'];
            $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'AppBundle:Admin/Filter:date.html.twig',
        ]);
    }

    public function getType()
    {
        return 'date';
    }
}
```

After you created your class, you need to create a service and register
it as a filter

```yaml
app.filter.date:
    class: AppBundle\Filter\DateFilter
    calls:
        - [setContainer, ['@service_container']]
    tags:
        - { name: enhavo.filter, alias: date }
```
