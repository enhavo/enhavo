<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Type;

use Enhavo\Bundle\ContentBundle\StructuredData\AbstractStructuredDataType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * StructuredDataType representing https://schema.org/CreativeWork
 *
 */
class CreativeWorkStructuredDataType extends AbstractStructuredDataType
{
    private static array $properties = [
        'about',
        'abstract',
        'accessMode',
        'accessModeSufficient',
        'accessibilityAPI',
        'accessibilityControl',
        'accessibilityFeature',
        'accessibilityHazard',
        'accessibilitySummary',
        'accountablePerson',
        'acquireLicensePage',
        'aggregateRating',
        'alternativeHeadline',
        'archivedAt',
        'assesses',
        'associatedMedia',
        'audience',
        'audio',
        'author',
        'award',
        'character',
        'citation',
        'comment',
        'commentCount',
        'conditionsOfAccess',
        'contentLocation',
        'contentRating',
        'contentReferenceTime',
        'contributor',
        'copyrightHolder',
        'copyrightNotice',
        'copyrightYear',
        'correction',
        'countryOfOrigin',
        'creativeWorkStatus',
        'creator',
        'creditText',
        'dateCreated',
        'dateModified',
        'datePublished',
        'digitalSourceType',
        'discussionUrl',
        'editEIDR',
        'editor',
        'educationalAlignment',
        'educationalLevel',
        'educationalUse',
        'encoding',
        'encodingFormat',
        'exampleOfWork',
        'expires',
        'funder',
        'funding',
        'genre',
        'hasPart',
        'headline',
        'inLanguage',
        'interactionStatistic',
        'interactivityType',
        'interpretedAsClaim',
        'isAccessibleForFree',
        'isBasedOn',
        'isFamilyFriendly',
        'isPartOf',
        'keywords',
        'learningResourceType',
        'license',
        'locationCreated',
        'mainEntity',
        'maintainer',
        'material',
        'materialExtent',
        'mentions',
        'offers',
        'pattern',
        'position',
        'producer',
        'provider',
        'publication',
        'publisher',
        'publisherImprint',
        'publishingPrinciples',
        'recordedAt',
        'releasedEvent',
        'review',
        'schemaVersion',
        'sdDatePublished',
        'sdLicense',
        'sdPublisher',
        'size',
        'sourceOrganization',
        'spatial',
        'spatialCoverage',
        'sponsor',
        'teaches',
        'temporal',
        'temporalCoverage',
        'text',
        'thumbnail',
        'thumbnailUrl',
        'timeRequired',
        'translationOfWork',
        'translator',
        'typicalAgeRange',
        'usageInfo',
        'version',
        'video',
        'wordCount',
        'workExample',
        'workTranslation',
    ];

    public function getTypeName(array $options): ?string
    {
        return 'CreativeWork';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedValues('property', self::$properties);
    }

    public static function getParentType(): ?string
    {
        return ThingStructuredDataType::class;
    }

    public static function getName(): ?string
    {
        return 'creative_work';
    }
}
