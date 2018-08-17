<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;

use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AutoGenerator
{
    use ContainerAwareTrait;

    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * @var array
     */
    private $autoGeneratorConfig;

    public function __construct(CollectorInterface $collector, $autoGeneratorConfig)
    {
        $this->collector = $collector;
        $this->autoGeneratorConfig = $autoGeneratorConfig;
    }

    public function generate(RouteInterface $route, $type, $options = [])
    {
        if($type === null) {

        }


        /** @var GeneratorInterface $type */
        $type = $this->collector->getType($type);
        $type->generate($route, $options);
    }
}