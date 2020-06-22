<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 22:35
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider;

use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Reference;
use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Metadata as BaseMetata;
use Enhavo\Component\Metadata\ProviderInterface;

class ReferenceProvider implements ProviderInterface
{
    public function provide(BaseMetata $metadata, $normalizedData)
    {
        if(!$metadata instanceof Metadata) {
            throw ProviderException::invalidType($metadata, Metadata::class);
        }

        if(array_key_exists('reference', $normalizedData) && is_array($normalizedData['reference'])) {
            foreach($normalizedData['reference'] as $name => $referenceData) {
                if(!is_array($referenceData)) {
                    throw ProviderException::definitionMissing($metadata, ['idField', 'nameField']);
                } elseif(!array_key_exists('nameField', $referenceData)) {
                    throw ProviderException::definitionMissing($metadata, 'nameField');
                } elseif(!array_key_exists('idField', $referenceData)) {
                    throw ProviderException::definitionMissing($metadata, 'idField');
                }
                $metadata->addReference(new Reference($name, $referenceData['nameField'], $referenceData['idField']));
            }
        }
    }
}
