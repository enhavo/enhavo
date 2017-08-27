<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 13:30
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

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
        $guesser = MimeTypeGuesser::getInstance();
        return $guesser->guess($path);
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
        $guesser = ExtensionGuesser::getInstance();
        return $guesser->guess($path);
    }
}