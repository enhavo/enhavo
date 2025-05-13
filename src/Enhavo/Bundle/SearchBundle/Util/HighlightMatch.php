<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Util;

class HighlightMatch
{
    /**
     * @var string
     */
    private $word;

    /**
     * @var string[]
     */
    private $behind = [];

    /**
     * @var string[]
     */
    private $forward = [];

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return string[]
     */
    public function getBehind()
    {
        return $this->behind;
    }

    /**
     * @param string[] $behind
     */
    public function setBehind($behind)
    {
        $this->behind = $behind;
    }

    /**
     * @return string[]
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * @param string[] $forward
     */
    public function setForward($forward)
    {
        $this->forward = $forward;
    }

    public function popBehind()
    {
        if (count($this->behind)) {
            return array_pop($this->behind);
        }

        return null;
    }

    public function popForward()
    {
        if (count($this->forward)) {
            return array_pop($this->forward);
        }

        return null;
    }
}
