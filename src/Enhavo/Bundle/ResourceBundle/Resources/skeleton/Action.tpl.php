<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?php echo $class_name; ?> extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data = array_merge($data, [

        ]);
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => '',
            'label' => '<?php echo $name; ?>',
            'icon' => '',
        ]);
    }

    public function getType()
    {
        return '<?php echo $name; ?>';
    }
}
