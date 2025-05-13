<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContactBundle\Widget;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
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
     * @var TemplateResolver
     */
    private $templateResolver;

    public function __construct(ContactManager $contactManager, TemplateResolver $templateResolver)
    {
        $this->contactManager = $contactManager;
        $this->templateResolver = $templateResolver;
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
        return $this->templateResolver->resolve($options['template']);
    }
}
