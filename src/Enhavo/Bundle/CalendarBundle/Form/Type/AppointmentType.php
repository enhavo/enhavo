<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', TextareaType::class, [
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('dateFrom', DateTimeType::class, [
            'label' => 'appointment.form.label.dateFrom',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('dateTo', DateTimeType::class, [
            'label' => 'appointment.form.label.dateTo',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('picture', MediaType::class, [
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
        ]);

        $builder->add('content', BlockNodeType::class, [
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['content'],
        ]);

        $builder->add('externalId', TextType::class, [
            'label' => 'appointment.form.label.externalId',
            'translation_domain' => 'EnhavoCalendarBundle',
            // 'read_only' => true
        ]);

        $builder->add('locationLongitude', TextType::class, [
            'label' => 'appointment.form.label.locationLongitude',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationLatitude', TextType::class, [
            'label' => 'appointment.form.label.locationLatitude',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationName', TextType::class, [
            'label' => 'appointment.form.label.locationName',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationCity', TextType::class, [
            'label' => 'appointment.form.label.locationCity',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationCountry', TextType::class, [
            'label' => 'appointment.form.label.locationCountry',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationStreet', TextType::class, [
            'label' => 'appointment.form.label.locationStreet',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('locationZip', TextType::class, [
            'label' => 'appointment.form.label.locationZip',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('repeatRule', TextType::class, [
            'label' => 'appointment.form.label.repeatRule',
            'translation_domain' => 'EnhavoCalendarBundle',
        ]);

        $builder->add('importerName', TextType::class, [
            'label' => 'appointment.form.label.importerName',
            'translation_domain' => 'EnhavoCalendarBundle',
            // 'read_only' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'slugable' => true,
            'data_class' => $this->dataClass,
        ]);
    }

    public function getParent()
    {
        return ContentType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_calendar_appointment';
    }
}
