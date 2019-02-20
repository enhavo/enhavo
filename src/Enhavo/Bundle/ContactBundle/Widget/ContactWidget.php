<?php
/**
 * ContactWidget.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ContactBundle\Configuration\ConfigurationFactory;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ContactWidget extends AbstractType implements WidgetInterface
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

    public function render($options)
    {
        $name = $this->getOption('name', $options, 'contact');
        $configuration = $this->configurationFactory->create($name);
        $template = $this->getOption('template', $options, $configuration->getFormTemplate()) ;
        $form = $formFactory = $this->formFactory->create($configuration->getForm());

        return $this->renderTemplate($template, array(
            'form' => $form->createView(),
            'name' => $name
        ));
    }
}
