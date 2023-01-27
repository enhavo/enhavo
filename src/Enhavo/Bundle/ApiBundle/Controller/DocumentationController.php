<?php

namespace App\Controller;

use App\Form\Type\Form\ItemsType;
use App\Form\Type\Form\ItemsWysiwygType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class DocumentationController extends AbstractController
{
    #[Route('/docs', name: "app_api_docs")]
    public function indexAction(Request $request)
    {
        return $this->render('theme/api/docs.html.twig');
    }
}
