<?php
/**
 * GridController.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\DynamicForm\ItemResolverInterface;
use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;

class DynamicFormController extends Controller
{
    public function itemAction(Request $request)
    {
        $resolver = $this->getResolver($request);

        $formName = $request->get('formName');
        $type = $request->get('type');

        $factory = $resolver->resolveFactory($type);
        $data = $factory->createNew();

        /** @var $form Form */
        $form = $resolver->resolveForm($type, $data, array(
            'csrf_protection' => false,
            'item_resolver' => $this->getResolverName($request),
            'item_full_name' => $formName
        ));


        return $this->render('EnhavoAppBundle:DynamicForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return ResolverInterface
     * @throws \Exception
     */
    private function getResolver(Request $request)
    {
        $resolverName = $this->getResolverName($request);

        if(!$this->container->has($resolverName)) {
            throw new \Exception(sprintf('Resolver "%s" for dynamic form controller not found', $resolverName));
        }
        $resolver = $this->container->get($resolverName);

        if(!$resolver instanceof ResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form controller is not implements ResolverInterface', $resolver));
        }

        return $resolver;
    }

    private function getResolverName(Request $request)
    {
        $resolverName = $request->attributes->get('_resolver');
        return $resolverName;
    }
} 
