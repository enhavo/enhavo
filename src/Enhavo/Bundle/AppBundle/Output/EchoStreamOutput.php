<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Output;

use Symfony\Component\Console\Output\StreamOutput;

class EchoStreamOutput extends StreamOutput
{
    protected function doWrite($message, $newline)
    {
        if (false === @fwrite($this->getStream(), $message) || ($newline && (false === @fwrite($this->getStream(), PHP_EOL)))) {
            throw new \RuntimeException('Unable to write output.');
        }

        echo $message."\n";

        ob_flush();
        flush();
    }
}
