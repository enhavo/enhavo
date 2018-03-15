<?php
/**
 * GridController.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;

class DynamicFormController extends Controller
{
    public function itemAction(Request $request)
    {
        $formName = $request->get('formName');
        $type = $request->get('type');

        $formFactory = $this->container->get('form.factory');

        $resolver = $this->container->get($request->attributes->get('_resolver'));

        /** @var $formType ItemFormType */
        $formType = $resolver->getFormType($type);
        $formType->setFormName($formName);

        $factory = $this->container->get($request->attributes->get('_factory'));
        $itemType = $factory->create($type);

        $form = $formFactory->create($formType, $itemType, array(
            'csrf_protection' => false,
        ));
        $label = $resolver->getLabel($type);

        return $this->render('EnhavoAppBundle:Form:form.html.twig', array(
            'formItem' => $form->createView(),
            'formName' => $formName,
            'formOrder' => 0,
            'formType' => $type,
            'label' => $label
        ));
    }
} 
