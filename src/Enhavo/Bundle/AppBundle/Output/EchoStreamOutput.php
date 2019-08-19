<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-19
 * Time: 16:04
 */

namespace Enhavo\Bundle\AppBundle\Output;

use RuntimeException;
use Symfony\Component\Console\Output\StreamOutput;

class EchoStreamOutput extends StreamOutput
{
    protected function doWrite($message, $newline)
    {
        if (false === @fwrite($this->getStream(), $message) || ($newline && (false === @fwrite($this->getStream(), PHP_EOL)))) {
            throw new RuntimeException('Unable to write output.');
        }

        echo $message . "\n";

        ob_flush();
        flush();
    }
}
