<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Validator\Constraints;

use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TermUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private TermRepository $termRepository,
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TermUnique) {
            throw new UnexpectedTypeException($constraint, TermUnique::class);
        }
        if (!($value instanceof TermInterface)) {
            return;
        }
        if (!$value->getName()) {
            return;
        }

        $similarTerms = $this->termRepository->findBy([
            'name' => $value->getName(),
            'taxonomy' => $value->getTaxonomy(),
        ]);
        $isDuplicate = false;
        foreach ($similarTerms as $similarTerm) {
            if ($similarTerm->getId() !== $value->getId()) {
                $isDuplicate = true;
                break;
            }
        }

        if ($isDuplicate) {
            $this->context
                ->buildViolation($constraint->message)
                ->setTranslationDomain($constraint->translationDomain)
                ->atPath('name')
                ->addViolation();
        }
    }
}
