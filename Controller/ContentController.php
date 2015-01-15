<?php
/**
 * ContentController.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Controller;

use esperanto\ContentBundle\Form\Type\PictureType;
use esperanto\ContentBundle\Form\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use esperanto\ContentBundle\Service\ContentTypeService;

class ContentController extends Controller
{
    public function itemAction(Request $request)
    {
        $formName = $request->get('formName');
        $name = $request->get('name');

        /** @var $formFactory FormFactory */
        $formFactory = $this->container->get('form.factory');

        /** @var $contentService ContentTypeService */
        $contentService = $this->container->get('esperanto_content.service.content');
        $formType = $contentService->getFormTypeResolver()->resolve($name, $formName);
        $form = $formFactory->create($formType)->createView();

        $template = sprintf('esperantoContentBundle:Form:form.html.twig', $name);

        return $this->render($template, array(
            'formItem' => $form,
            'formName' => $formName,
            'formOrder' => 0,
            'created' => true
        ));
    }
} 