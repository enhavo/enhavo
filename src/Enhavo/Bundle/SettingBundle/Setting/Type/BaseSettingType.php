<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\SettingBundle\Entity\Setting as SettingEntity;
use Enhavo\Bundle\SettingBundle\Exception\SettingNotExists;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Enhavo\Bundle\SettingBundle\Setting\SettingTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSettingType extends AbstractSettingType implements SettingTypeInterface
{
    public function __construct(
        private readonly EntityRepository $repository,
        private readonly Factory $factory,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function getSettingEntity($options, $key): SettingEntity
    {
        /** @var SettingEntity $settingEntity */
        $settingEntity = $this->repository->findOneBy(['key' => $key]);
        if (null === $settingEntity) {
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

        if (null === $settingEntity) {
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
            'label' => null,
        ]);
    }
}
