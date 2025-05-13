<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalGroupType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'subscriber.form.label.groups',
            'groups' => null,
            'class' => Group::class,
            'multiple' => true,
            'expanded' => true,
        ]);

        $resolver->setDefault('query_builder', function (Options $options) {
            return function (EntityRepository $er) use ($options) {
                $queryBuilder = $er->createQueryBuilder('s');
                if ($options['groups']) {
                    foreach ($options['groups'] as $group) {
                        $groupKey = md5($group);
                        $queryBuilder->orWhere(sprintf('s.code = :%s', $groupKey))
                            ->setParameter($groupKey, $group);
                    }
                }

                return $queryBuilder;
            };
        });
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_local_group';
    }
}
