<?php
/**
 * RoutingGenerator.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class RoutingGenerator
{
    protected $templateEngine;

    public function __construct(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    public function generate($appName, $resourceName, $sorting = null)
    {
        return $this->templateEngine->render('EnhavoGeneratorBundle:Generator:routing.yml.twig',
            array(
                'app' => $appName,
                'resource' => $resourceName,
                'sorting' => $sorting,
                'app_url' => $this->getUrl($appName),
                'resource_url' => $this->getUrl($resourceName)
            )
        );
    }

    protected function getUrl($input)
    {
        return preg_replace('/_/', '/', $input);
    }
}