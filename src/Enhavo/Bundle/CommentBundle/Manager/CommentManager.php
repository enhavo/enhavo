<?php

namespace Enhavo\Bundle\CommentBundle\Manager;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentManager
{
    private readonly PropertyAccessor $propertyAccessor;

    public function __construct(
        private readonly array $subjects,
        private readonly TranslatorInterface $translator,
        private readonly ResourceManager $resourceManager,
    )
    {
        $this->propertyAccessor = new PropertyAccessor;
    }

    public function getSubjectLabel(CommentInterface $comment): ?string
    {
        $config = $this->getSubjectConfig($comment->getSubject());

        if ($config === null) {
            return '';
        }


        $label = null;
        if (isset($config['label'])) {
            $label =  $this->translator->trans($config['label'], [], $config['translation_domain']);
        } else {
            $metadata = $this->resourceManager->getMetadata($comment->getSubject());
            if ($metadata) {
                $label =  $this->translator->trans($metadata->getLabel(), [], $metadata->getTranslationDomain());
            }
        }

        return $label;
    }

    public function getSubjectTitle(CommentInterface $comment): ?string
    {
        $config = $this->getSubjectConfig($comment->getSubject());

        if ($config === null) {
            return '';
        }

        return $this->propertyAccessor->getValue($comment->getSubject(), $config['title_property']);
    }

    private function getSubjectConfig(object|string $subject): ?array
    {
        $class = is_string($subject) ? $subject : get_class($subject);
        foreach ($this->subjects as $subjectName => $config) {
            if ($class === $subjectName) {
                return $config;
            }
        }

        $parent = get_parent_class($subject);
        if ($parent !== false) {
            return $this->getSubjectConfig($parent);
        }

        return null;
    }
}
