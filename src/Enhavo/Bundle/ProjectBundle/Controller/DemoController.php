<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Enhavo\Bundle\AppBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\AppBundle\Form\Type\DateType;
use Enhavo\Bundle\AppBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Form\Type\GridType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;
use Enhavo\Bundle\NavigationBundle\Form\Type\NavigationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends AbstractController
{
    public function indexAction(Request $request)
    {
        return $this->render('EnhavoProjectBundle:Theme/Demo:index.html.twig');
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

        return $this->render('EnhavoProjectBundle:Theme/Demo:media.html.twig', [
            'form' => $formView
        ]);
    }

    public function gridAction(Request $request)
    {
        $grid = new Grid();

        $form = $this->createForm(GridType::class, $grid);

        if($request->isMethod('post')) {
            $form->submit($request);
        }

        $formView = $form->createView();

        return $this->render('EnhavoProjectBundle:Theme/Demo:grid.html.twig', [
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

        return $this->render('EnhavoProjectBundle:Theme/Demo:navigation.html.twig', [
            'form' => $formView
        ]);
    }

    public function applicationAction()
    {
        return $this->render('EnhavoProjectBundle:Theme/Demo:application.html.twig', [

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
            ->getForm();


        return $this->render('EnhavoProjectBundle:Theme/Demo:form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}