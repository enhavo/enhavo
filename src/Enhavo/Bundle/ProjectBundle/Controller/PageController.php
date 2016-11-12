<?php
/**
 * PageController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Enhavo\Bundle\PageBundle\Controller\PageController as EnhavoPageController;

class PageController extends EnhavoPageController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoProjectBundle:Theme:Page/show.html.twig', [
            'page' => $contentDocument,
        ]);
    }
}