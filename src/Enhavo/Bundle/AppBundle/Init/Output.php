<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-26
 * Time: 21:51
 */

namespace Enhavo\Bundle\AppBundle\Init;

use Symfony\Component\Console\Output\OutputInterface;

class Output
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function writeln($message)
    {
        $this->output->writeln($message);
    }

    public function write($message)
    {
        $this->output->write($message);
    }
}
