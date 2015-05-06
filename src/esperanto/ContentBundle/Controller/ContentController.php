<?php
/**
 * ContentController.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Controller;

use esperanto\ContentBundle\Entity\Content;
use esperanto\ContentBundle\Entity\Item;
use esperanto\ContentBundle\Entity\Text;
use esperanto\ContentBundle\Entity\Picture;
use esperanto\ProjectBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends Controller
{
    public function itemAction(Request $request)
    {
        $formName = $request->get('formName');
        $type = $request->get('type');

        $formFactory = $this->container->get('form.factory');

        $resolver = $this->container->get('esperanto_content.item_type_resolver');
        $formType = $resolver->getFormType($type);
        $formType->setFormName($formName);
        $form = $formFactory->create($formType)->createView();

        return $this->render('esperantoContentBundle:Form:form.html.twig', array(
            'formItem' => $form,
            'formName' => $formName,
            'formOrder' => 0,
            'formType' => $type,
            'created' => true
        ));
    }
} 
