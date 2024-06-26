<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $class_name; ?> extends AbstractActionType implements ActionTypeInterface
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
            'label' => '<?= $name; ?>',
            'icon' => '',
        ]);
    }

    public function getType()
    {
        return '<?= $name; ?>';
    }
}
