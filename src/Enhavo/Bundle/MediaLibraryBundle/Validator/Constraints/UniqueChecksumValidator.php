<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Validator\Constraints;

use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueChecksumValidator extends ConstraintValidator
{
    public function __construct(
        public ItemRepository $itemRepository,
        public TranslatorInterface $translator,
    )
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof LibraryFileInterface) {
            throw new UnexpectedTypeException($value, LibraryFileInterface::class);
        }

        /** @var ItemInterface[] $items */
        $items = $this->itemRepository->findByChecksum($value->getChecksum());

        if (count($items)) {
            $this->context->buildViolation($this->translator->trans('upload.error.unique_checksum', ['basename' => $items[0]->getFile()->getBasename()], 'EnhavoMediaLibraryBundle'))
                ->addViolation();
        }
    }
}
