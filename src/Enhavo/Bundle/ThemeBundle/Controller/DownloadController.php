<?php
/**
 * DownloadController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{
    public function indexAction()
    {
        $downloads = $this->get('enhavo_download.repository.download')->findAll();

        return $this->render('EnhavoThemeBundle:Theme:Download/index.html.twig', [
            'downloads' => $downloads
        ]);
    }
}