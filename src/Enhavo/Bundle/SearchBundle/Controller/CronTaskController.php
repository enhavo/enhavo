<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Enhavo\Bundle\SearchBundle\Entity\CronTask;
use Symfony\Component\HttpFoundation\Response;

class CronTaskController extends Controller
{
    public function indexingAction()
    {
        $entity = new CronTask();

        $entity ->setName('Indexing Data')
                ->setIval(30) // Run once every hour
                ->setCommands(array(
                    'indexing:run'
                ));

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new Response('OK!');
    }
}