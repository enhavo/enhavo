<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\SettingBundle\Entity\Setting as SettingEntity;
use Enhavo\Bundle\SettingBundle\Exception\SettingNotExists;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Enhavo\Bundle\SettingBundle\Setting\SettingTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSettingType extends AbstractSettingType implements SettingTypeInterface
{
    /** @var EntityRepository */
    private $repository;

    /** @var Factory */
    private $factory;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * BaseSettingType constructor.
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

    public function getSettingEntity($options, $key): SettingEntity
    {
        /** @var SettingEntity $settingEntity */
        $settingEntity = $this->repository->findOneBy(['key' => $key]);
        if ($settingEntity === null) {
            $settingEntity = $this->factory->createNew();
            $settingEntity->setKey($key);
            $this->em->persist($settingEntity);
        }

        $settingEntity->setLabel($options['label'] ?? $key);
        $settingEntity->setTranslationDomain($options['translation_domain']);
        $settingEntity->setGroup($options['group']);

        return $settingEntity;
    }

    public function init(array $options, $key = null)
    {
        $this->getSettingEntity($options, $key);
    }

    public function getFormTypeOptions(array $options, $key = null)
    {
        return [];
    }

    public function getValue(array $options, $key = null)
    {
        /** @var SettingEntity $settingEntity */
        $settingEntity = $this->repository->findOneBy(['key' => $key]);

        if ($settingEntity === null) {
            throw SettingNotExists::entityNotFound();
        }

        return $settingEntity->getValue();
    }

    public function getGroup(array $options, $key = null)
    {
        return $options['group'];
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
