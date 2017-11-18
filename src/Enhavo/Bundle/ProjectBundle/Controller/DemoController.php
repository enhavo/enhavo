<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Form\Type\GridType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
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
}