<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-19
 * Time: 13:30
 */

namespace Enhavo\Bundle\ThemeBundle\Controller;

use Enhavo\Bundle\AppBundle\Output\EchoStreamOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ThemeController extends AbstractController
{
    public function buildAction()
    {
        $application = new Application($this->container->get('kernel'));

        return new StreamedResponse(function() use ($application) {
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'enhavo:theme:webpack:build',
            ]);

            $output = new EchoStreamOutput(fopen('php://stdout', 'w'), OutputInterface::VERBOSITY_NORMAL, true);
            $application->run($input, $output);
        });
    }
}
