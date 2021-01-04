<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-17
 * Time: 22:32
 */

namespace Enhavo\Bundle\AppBundle\Init;

use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitManager
{
    /**
     * @var CollectorInterface
     */
    private $collector;

    /**
     * InitManager constructor.
     * @param CollectorInterface $collector
     */
    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function init(OutputInterface $output)
    {
        $io = new Output($output);
        /** @var InitInterface $initializer */
        foreach($this->collector->getTypes() as $initializer) {
            $io->writeln('Initializer');
            $initializer->init($io);
        }
    }
}
