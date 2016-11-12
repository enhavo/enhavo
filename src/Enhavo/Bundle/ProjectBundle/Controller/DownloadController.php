<?php
/**
 * DownloadController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{
    public function indexAction()
    {
        $downloads = $this->get('enhavo_download.repository.download')->findAll();

        return $this->render('EnhavoProjectBundle:Theme:Download/index.html.twig', [
            'downloads' => $downloads
        ]);
    }
}