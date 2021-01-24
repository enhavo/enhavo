<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\SettingBundle\Entity\Setting as SettingEntity;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Enhavo\Bundle\SettingBundle\Setting\SettingTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntitySettingType extends AbstractSettingType implements SettingTypeInterface
{
    /** @var EntityRepository */
    private $repository;

    /** @var Factory */
    private $factory;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * EntitySettingType constructor.
     * @param EntityRepository $repository
     * @param Factory $factory
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityRepository $repository, Factory $factory, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->em = $em;
    }

    public function getSettingEntity($options): SettingEntity
    {
        /** @var SettingEntity $settingEntity */
        $settingEntity = $this->repository->findOneBy(['key' => $this->key]);
        if ($settingEntity === null) {
            $settingEntity = $this->factory->createNew();
            $settingEntity->setKey($this->key);
            $this->em->persist($settingEntity);
        }

        $settingEntity->setLabel($options['label'] ?? $this->key);
        $settingEntity->setTranslationDomain($options['translation_domain']);
        $settingEntity->setGroup($options['group']);

        return $settingEntity;
    }

    public function init(array $options)
    {

    }

    public function getValue(array $options)
    {
        /** @var SettingEntity $settingEntity */
        $settingEntity = $this->repository->findOneBy(['key' => $this->key]);

        if ($settingEntity === null || $settingEntity->getValue() === null) {
            return null;
        }

        $settingEntity->getValue()->getValue();
    }

    public static function getName(): ?string
    {
        return 'entity';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'group' => null,
            'default' => null,
            'label' => null
        ]);
    }
}
