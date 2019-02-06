<?php
/**
 * RoutingGenerator.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GeneratorBundle\Generator;

class RoutingGenerator
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * RoutingGenerator constructor.
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function generate($appName, $resourceName, $sorting = null)
    {
        return $this->generator->render('EnhavoGeneratorBundle:Generator:routing.yml.twig',
            array(
                'app' => $appName,
                'resource' => $resourceName,
                'sorting' => $sorting,
                'app_url' => $this->getUrl($appName),
                'resource_url' => $this->getUrl($resourceName)
            )
        );
    }

    private function getUrl($input)
    {
        return preg_replace('/_/', '/', $input);
    }
}
