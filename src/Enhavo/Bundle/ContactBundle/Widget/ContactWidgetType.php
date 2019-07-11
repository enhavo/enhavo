<?php
/**
 * ContactWidgetType.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\ContactBundle\Configuration\ConfigurationFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactWidgetType extends AbstractWidgetType
{
    /**
     * @var ConfigurationFactory
     */
    private $configurationFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(ConfigurationFactory $configurationFactory, FormFactoryInterface $formFactory)
    {
        $this->configurationFactory = $configurationFactory;
        $this->formFactory = $formFactory;
    }

    public function getType()
    {
        return 'contact';
    }

    public function createViewData($options, $resource = null)
    {
        $configuration = $this->configurationFactory->create($options['name']);
        $form = $this->formFactory->create($configuration->getForm());

        return [
            'form' => $form->createView(),
            'name' => $options['name'],
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'name' => 'contact',
            'template' => null,
        ]);
    }

    public function getTemplate($options)
    {
        if ($options['template']) {
            return $options['template'];
        }

        $configuration = $this->configurationFactory->create($options['name']);
        $template = $configuration->getFormTemplate();
        if ($template) {
            return $template;
        }

        return 'theme/widget/contact.html.twig';
    }
}
