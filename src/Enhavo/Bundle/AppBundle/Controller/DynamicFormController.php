<?php
/**
 * GridController.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\DynamicForm\ItemResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;

class DynamicFormController extends Controller
{
    public function __construct()
    {

    }

    public function itemAction(Request $request)
    {
        $resolver = $this->getResolver($request);

        $formName = $request->get('formName');
        $type = $request->get('type');

        $formFactory = $this->container->get('form.factory');

        /** @var $formType ItemFormType */
        $formType = $resolver->getFormType($type);
        $formType->setFormName($formName);

        $factory = $this->container->get($request->attributes->get('_factory'));
        $itemType = $factory->create($type);

        $form = $formFactory->create($formType, $itemType, array(
            'csrf_protection' => false,
        ));
        $label = $resolver->getItem($type)->getLabel();

        return $this->render('EnhavoAppBundle:DynamicForm:form.html.twig', array(
            'formItem' => $form->createView(),
            'formName' => $formName,
            'formOrder' => 0,
            'formType' => $type,
            'label' => $label
        ));
    }

    /**
     * @param Request $request
     * @return ItemResolverInterface
     * @throws \Exception
     */
    private function getResolver(Request $request)
    {
        $resolverName = $request->attributes->get('_resolver');

        if(!$this->container->has($resolverName)) {
            throw new \Exception(sprintf('Resolver "%s" for dynamic form controller not found', $resolverName));
        }
        $resolver = $this->container->get($resolverName);

        if(!$resolver instanceof ItemResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form controller is not implements ItemResolverInterface', $resolver));
        }

        return $resolver;
    }
} 
