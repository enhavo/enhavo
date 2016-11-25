<?php

namespace Enhavo\Bundle\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
/**
 * TranslationString
 */
class TranslationString implements ResourceInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $translationKey;

    /**
     * @var string
     */
    private $translationValue;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set translationKey
     *
     * @param string $translationKey
     * @return TranslationString
     */
    public function setTranslationKey($translationKey)
    {
        $this->translationKey = $translationKey;

        return $this;
    }

    /**
     * Get translationKey
     *
     * @return string 
     */
    public function getTranslationKey()
    {
        return $this->translationKey;
    }

    /**
     * Set translationValue
     *
     * @param string $translationValue
     * @return TranslationString
     */
    public function setTranslationValue($translationValue)
    {
        $this->translationValue = $translationValue;

        return $this;
    }

    /**
     * Get translationValue
     *
     * @return string 
     */
    public function getTranslationValue()
    {
        return $this->translationValue;
    }
}
