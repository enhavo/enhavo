<?php

namespace esperanto\PageBundle\Controller;

use esperanto\AdminBundle\View\TabCollection;
use esperanto\PageBundle\Entity\Page;
use esperanto\PageBundle\Entity\Container;
use esperanto\MediaBundle\Entity\File;
use esperanto\PageBundle\Entity\Paragraph;
use esperanto\PageBundle\Form\Type\ContainerType;
use esperanto\PageBundle\Form\Type\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use esperanto\PageBundle\Entity\Task;
use esperanto\PageBundle\Entity\Tag;
use esperanto\PageBundle\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;

class DialogController extends Controller
{
    public function addAction(Request $request)
    {


        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        /*

        $task = new Task();
        $tag1 = new Tag();
        $tag1->name = 'tag1';
        $task->getTags()->add($tag1);
        $tag2 = new Tag();
        $tag2->name = 'tag2';
        $task->getTags()->add($tag2);
        // end dummy code

        $form = $this->createForm(new TaskType(), $task);
        */

        $container = new Container();

        $paragraph = new Paragraph();
        $paragraph->setName('tag1');
        $container->addParagraph($paragraph);
        $paragraph = new Paragraph();
        $paragraph->setName('tag2');
        $container->addParagraph($paragraph);
        // end dummy code

        $form = $this->createForm(new ContainerType(), $container);


        $form->handleRequest($request);

        if ($form->isValid()) {
            // ... maybe do some form processing, like saving the Task and Tag objects
        }

        $tabs = new TabCollection();
        $tabs->addTab()->setForm($form)->setLabel('Tab1');

        return $this->render('esperantoPageBundle:Dialog:add.html.twig', array(
            'tabs' => $tabs
        ));
    }
}
