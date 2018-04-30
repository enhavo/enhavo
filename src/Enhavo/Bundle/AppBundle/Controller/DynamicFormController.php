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
        $factory = $resolver->getFactory($type);
        $data = $factory->create();

        $form = $formFactory->create($formType, $data, array(
            'csrf_protection' => false,
            'item_resolver' => $this->getResolverName($request),
            'item_full_name' => $formName
        ));

        $label = $resolver->getItem($type)->getLabel();

        return $this->render('EnhavoAppBundle:DynamicForm:form.html.twig', array(
            'form' => $form->createView(),
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
        $resolverName = $this->getResolverName($request);

        if(!$this->container->has($resolverName)) {
            throw new \Exception(sprintf('Resolver "%s" for dynamic form controller not found', $resolverName));
        }
        $resolver = $this->container->get($resolverName);

        if(!$resolver instanceof ItemResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form controller is not implements ItemResolverInterface', $resolver));
        }

        return $resolver;
    }

    private function getResolverName(Request $request)
    {
        $resolverName = $request->attributes->get('_resolver');
        return $resolverName;
    }
} 
