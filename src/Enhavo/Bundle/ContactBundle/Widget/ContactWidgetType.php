<?php
/**
 * ContactWidgetType.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Widget;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\ContactBundle\Contact\ContactManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactWidgetType extends AbstractWidgetType
{
    /**
     * @var ContactManager
     */
    private $contactManager;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    public function __construct(ContactManager $contactManager, TemplateManager $templateManager)
    {
        $this->contactManager = $contactManager;
        $this->templateManager = $templateManager;
    }

    public function getType()
    {
        return 'contact';
    }

    public function createViewData(array $options, $resource = null)
    {
        $form = $this->contactManager->createForm($options['key']);

        return [
            'form' => $form->createView(),
            'key' => $options['key'],
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'key' => 'default',
            'template' => $this->contactManager->getTemplate('default', 'submit'),
        ]);
    }

    public function getTemplate($options)
    {
        return $this->templateManager->getTemplate($options['template']);
    }
}
