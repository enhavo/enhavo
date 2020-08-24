<?php

namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TranslationValidator extends ConstraintValidator
{
    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * TranslationValidator constructor.
     * @param TranslationManager $translationManager
     * @param ValidatorInterface $validator
     */
    public function __construct(TranslationManager $translationManager, ValidatorInterface $validator)
    {
        $this->translationManager = $translationManager;
        $this->validator = $validator;
    }

    public function validate($value, Constraint $constraint)
    {
        $metadata = $this->context->getMetadata();

        if (!($metadata instanceof PropertyMetadataInterface)) {
            throw new RuntimeException(sprintf('Expected "%s" for metadata but got "%s"', PropertyMetadataInterface::class, get_class($metadata)));
        }

        $resource = $this->context->getObject();
        $property = $metadata->getPropertyName();

        $constraints = $constraint->constraints;

        $translations = $this->translationManager->getTranslations($resource, $property);
        if ($constraint->validateDefaultValue) {
            $translations[$this->translationManager->getDefaultLocale()] = $value;
        }

        foreach ($translations as $locale => $translation) {
            foreach ($constraints as $translationConstraint) {
                /** @var ConstraintViolationInterface[] $violations */
                $violations = $this->validator->validate($translation, $translationConstraint);
                foreach ($violations as $violation) {
                    $this->context->buildViolation(sprintf('(%s) %s', $locale, $violation->getMessage()))->addViolation();
                }
            }
        }
    }
}
