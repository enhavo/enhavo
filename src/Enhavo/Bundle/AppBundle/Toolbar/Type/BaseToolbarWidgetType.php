<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarWidgetTypeInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseToolbarWidgetType extends AbstractType implements ToolbarWidgetTypeInterface
{
    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('component', $options['component']);
        $data->set('model', $options['model']);
    }

    public function getPermission(array $options, ResourceInterface $resource = null): mixed
    {
        return $options['permission'];
    }

    public function isEnabled(array $options, ResourceInterface $resource = null): bool
    {
        return $options['enabled'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'enabled' => true,
            'model' => 'BaseToolbarWidget',
            'permission' => null,
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }
}
