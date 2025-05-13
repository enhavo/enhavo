<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?php echo $class_name; ?> extends AbstractFilterType
{
    public function createViewData($options, $name)
    {
        $data = parent::createViewData($options, $name);
        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {

    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => ''
        ]);
        $optionsResolver->setRequired([]);
    }

    public function getType()
    {
        return '<?php echo $name; ?>';
    }
}
