<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-14
 * Time: 12:06
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModalController extends AbstractController
{
    use TemplateResolverTrait;

    public function __construct(
        private FormFactoryInterface $formFactory
    ) {}

    public function formAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);
        $form = $this->formFactory->create($configuration->getForm(), null, $configuration->getOptions());
        $template = $configuration->getTemplate() ? $configuration->getTemplate() : 'admin/view/modal-form.html.twig';

        $form->handleRequest($request);

        $response = new Response();
        if ($form->isSubmitted() && !$form->isValid()) {
            $response->setStatusCode(400);
        }

        return $this->render($this->resolveTemplate($template), [
            'form' => $form->createView()
        ], $response);
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

        if(isset($config['template'])) {
            $configuration->setTemplate($config['template']);
        }

        return $configuration;
    }
}
