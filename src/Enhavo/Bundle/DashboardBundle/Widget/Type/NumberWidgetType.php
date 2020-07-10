<?php


namespace Enhavo\Bundle\DashboardBundle\Widget\Type;


use Enhavo\Bundle\AppBundle\Provider\ProviderInterface;
use Enhavo\Bundle\AppBundle\Provider\ProviderManager;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Widget\AbstractWidgetType;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberWidgetType extends AbstractWidgetType
{
    /**
     * @var ProviderManager
     */
    private $providerManager;

    public function __construct(ProviderManager $providerManager)
    {
        $this->providerManager = $providerManager;
    }

    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $providerOptions = $options['provider'];
        $data['value'] = $this->get($providerOptions['type'], $providerOptions['key']);
    }

    public function get($type, $key)
    {
        /** @var ProviderInterface $provider */
        $provider = $this->providerManager->getProvider($type, $key);
        return $provider->get();
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
