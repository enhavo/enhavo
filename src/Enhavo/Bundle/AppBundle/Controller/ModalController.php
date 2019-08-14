<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-14
 * Time: 12:06
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ModalController extends AbstractController
{
    use TemplateTrait;

    public function formAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);
        $form = $this->get('form.factory')->create($configuration->getForm(), null, $configuration->getOptions());
        $template = $configuration->getTemplate() ? $configuration->getTemplate() : 'admin/view/modal-form.html.twig';

        return $this->render($this->getTemplate($template), [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return ModalConfiguration
     */
    private function createConfiguration(Request $request): ModalConfiguration
    {
        $config = $request->attributes->get('_config', []);
        $configuration = new ModalConfiguration();

        if(!is_array($config)) {
            throw new \InvalidArgumentException('The config has to be an array');
        }

        if(!isset($config['form'])) {
            throw new \InvalidArgumentException('Form is missing');
        }
        $configuration->setForm($config['form']);

        if(isset($config['options'])) {
            $configuration->setOptions($config['options']);
        }

        return $configuration;
    }
}
