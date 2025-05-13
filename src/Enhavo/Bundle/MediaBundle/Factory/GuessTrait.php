<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Symfony\Component\Mime\MimeTypes;

trait GuessTrait
{
    /**
     * Guess the mime type of a file.
     *
     * @param string $path Full file name including path and extension
     *
     * @return string|null Mime type of null if none found
     */
    protected function guessMimeType($path)
    {
        $guesser = MimeTypes::getDefault();

        return $guesser->guessMimeType($path);
    }

    /**
     * Guess extension
     *
     * If the mime type is unknown, returns null.
     *
     * @param string $path
     *
     * @return string|null The guessed extension or null if it cannot be guessed
     */
    protected function guessExtension($path)
    {
        $guesser = MimeTypes::getDefault();
        $extensions = $guesser->getExtensions($this->guessMimeType($path));
        if ($extensions && count($extensions)) {
            return $extensions[0];
        }

        return null;
    }
}
