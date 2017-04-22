<?php
/**
 * TranslationStringTranslator.php
 *
 * @since 22/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\TranslationString;

use Sylius\Component\Resource\Repository\RepositoryInterface;

class TranslateStringTranslator
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function translate($key)
    {
        $translation = $this->repository->findOneBy(array('translationKey' => $key));
        if($translation === null) {
            return $key;
        }
        if($translation->getTranslationValue() == '' || $translation->getTranslationValue() === null) {
            return $key;
        }
        return $translation->getTranslationValue();
    }
}