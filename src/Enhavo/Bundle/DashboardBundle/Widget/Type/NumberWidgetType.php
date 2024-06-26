<?php


namespace Enhavo\Bundle\DashboardBundle\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Widget\AbstractWidgetType;
use Enhavo\Component\Type\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberWidgetType extends AbstractWidgetType
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * NumberWidgetType constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $provider = $this->factory->create($options['provider']);
        $data['value'] = $provider->getData();
    }

    public static function getName(): ?string
    {
        return 'number';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'number-widget',
        ]);

        $resolver->setRequired([
            'provider'
        ]);
    }
}
