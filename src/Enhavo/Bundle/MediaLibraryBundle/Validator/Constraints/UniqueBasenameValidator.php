<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Validator\Constraints;

use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueBasenameValidator extends ConstraintValidator
{
    public function __construct(
        public ItemRepository $itemRepository,
        public TranslatorInterface $translator,
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof LibraryFileInterface) {
            throw new UnexpectedTypeException($value, LibraryFileInterface::class);
        }

        if (count($this->itemRepository->findByBasename($value->getBasename()))) {
            $this->context->buildViolation($this->translator->trans('upload.error.unique_basename', ['basename' => $value->getBasename()], 'EnhavoMediaLibraryBundle'))
                ->addViolation();
        }
    }
}
