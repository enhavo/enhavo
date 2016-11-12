<?php
/**
 * PageController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function showAction($contentDocument)
    {
        return $this->render('EnhavoProjectBundle:Theme:Page/show.html.twig', [
            'page' => $contentDocument,
        ]);
    }
}