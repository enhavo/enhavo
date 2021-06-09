<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SettingBundle\Entity\MediaValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MediaSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property ValueAccessSettingType $parent
 */
class MediaSettingType extends AbstractSettingType
{
    /** @var FileFactory */
    private $fileFactory;

    /**
     * MediaSettingType constructor.
     * @param FileFactory $fileFactory
     */
    public function __construct(FileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new MediaValue($options['multiple'], $settingEntity));

            if ($options['multiple'] && is_array($options['default']) && count($options['default'])) {
                $files = new ArrayCollection();
                foreach ($options['default'] as $path) {
                    $file = $this->fileFactory->createFromPath($path);
                    $files->add($file);
                }
                $settingEntity->getValue()->setValue($files);
            } else if (!$options['multiple'] && $options['default'] !== null) {
                $file = $this->fileFactory->createFromPath($options['default']);
                $settingEntity->getValue()->setValue($file);
            }
        }
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        if ($options['multiple']) {
            $data = [];
            /** @var FileInterface $file */
            foreach($value->getValue() as $file) {
                $data[] = $file->getFilename();
            }
            return join(', ', $data);
        } else {
            $file = $value->getValue();
            if ($file !== null) {
                return $file->getFilename();
            }
        }
        return '';
    }

    public static function getName(): ?string
    {
        return 'media';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => false,
            'form_type' => MediaType::class
        ]);

        $resolver->setNormalizer('form_options', function($options, $value) {
            return array_merge([
                'multiple' => $options['multiple'],
            ], $value);
        });
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }
}
