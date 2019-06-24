<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace Enhavo\Bundle\DemoBundle\Controller;

use Enhavo\Bundle\BlockBundle\Entity\Container;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;
use Enhavo\Bundle\NavigationBundle\Form\Type\NavigationType;
use Enhavo\Bundle\DemoBundle\Form\Type\ContentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends AbstractController
{
    public function indexAction(Request $request)
    {
        return $this->render('EnhavoDemoBundle:Theme/Demo:index.html.twig');
    }

    public function mediaAction(Request $request)
    {
        $files = [];

        $form = $this->createForm(MediaType::class, $files, [
            'multiple' => true
        ]);

        if($request->isMethod('post')) {
            $form->submit($request);
        }

        $formView = $form->createView();

        return $this->render('EnhavoDemoBundle:Theme/Demo:media.html.twig', [
            'form' => $formView
        ]);
    }

    public function containerAction(Request $request)
    {
        $container = new Container();

        $form = $this->createForm(BlockNodeType::class, $container);

        if($request->isMethod('post')) {
            $form->submit($request);
        }

        $formView = $form->createView();

        return $this->render('EnhavoDemoBundle:Theme/Demo:container.html.twig', [
            'form' => $formView
        ]);
    }

    public function navigationAction(Request $request)
    {
        $navigation = new Navigation();

        $form = $this->createForm(NavigationType::class, $navigation);

        if($request->isMethod('post')) {
            $form->submit($request);
        }

        $formView = $form->createView();

        return $this->render('EnhavoDemoBundle:Theme/Demo:navigation.html.twig', [
            'form' => $formView
        ]);
    }

    public function applicationAction()
    {
        return $this->render('EnhavoDemoBundle:Theme/Demo:application.html.twig', [

        ]);
    }

    public function formAction(Request $request)
    {

        $form = $this->createFormBuilder(null)
            ->add('date', DateType::class, [])
            ->add('datetime', DateTimeType::class, [])
            ->add('wysiwyg', WysiwygType::class, [])
            ->add('checkbox', ChoiceType::class, [
                'choices' => ['foo', 'bar', 'hello', 'world'],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('select', ChoiceType::class, [
                'choices' => ['foo', 'bar', 'hello', 'world'],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('list', ListType::class, [
                'entry_type' => ContentType::class,
                'sortable' => true,
                'border' => true
            ])
            ->getForm();

        $form->handleRequest($request);
        return $this->render('EnhavoDemoBundle:Theme/Demo:form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
