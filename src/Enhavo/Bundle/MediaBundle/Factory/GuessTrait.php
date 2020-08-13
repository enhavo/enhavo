<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 13:30
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Symfony\Component\Mime\MimeTypes;

trait GuessTrait
{
    /**
     * Guess the mime type of a file.
     *
     * @param string $path Full file name including path and extension
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