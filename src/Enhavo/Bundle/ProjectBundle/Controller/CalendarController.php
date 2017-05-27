<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 28.04.17
 * Time: 16:19
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalendarController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnhavoProjectBundle:Theme:Calendar/index.html.twig', [

        ]);
    }
}