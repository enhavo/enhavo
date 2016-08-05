<?php
/**
 * PageController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function showAction($contentDocument)
    {
        return $this->render('EnhavoThemeBundle:Theme:Page/show.html.twig', [
            'page' => $contentDocument,
        ]);
    }
}