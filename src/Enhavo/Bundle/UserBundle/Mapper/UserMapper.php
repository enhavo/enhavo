<?php
/**
 * @author blutze-media
 * @since 2020-10-29
 */

namespace Enhavo\Bundle\UserBundle\Mapper;


use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class UserMapper implements UserMapperInterface
{
    const REGISTER_PROPERTIES = 'register_properties';
    const CREDENTIAL_PROPERTIES = 'credential_properties';

    public function __construct(
        private array $config,
    )
    {
    }

    public function getCredentialProperties(): array
    {
        return $this->config[self::CREDENTIAL_PROPERTIES];
    }

    /**
     * @throws PropertyNotExistsException
     */
    public function setUsername(UserInterface $user, ?array $credentials = null)
    {
        if ($credentials === null) {
            $credentials = $this->getCredentials($user);
        }

        $user->setUsername($this->getUsername($credentials));
    }

    private function getCredentials(UserInterface $user): array
    {
        $properties = $this->getCredentialProperties();
        $values = [];
        $propertyAccessor = new PropertyAccessor();

        foreach ($properties as $property) {
            if ($propertyAccessor->isReadable($user, $property)) {
                $values[$property] = $propertyAccessor->getValue($user, $property);
            }
        }

        return $values;
    }

    /**
     * @throws PropertyNotExistsException
     */
    public function getUsername(array $credentials): string
    {
        $properties = $this->getCredentialProperties();
        $values = [];
        foreach ($properties as $property) {
            if (isset($credentials[$property])) {
                $values[] = $credentials[$property];
            } else {
                throw new PropertyNotExistsException(sprintf('No property "%s" found in credentials', $property));
            }
        }

        return strtolower(implode($this->config['glue'], $values));
    }

    public function getRegisterProperties(): array
    {
        return $this->config[self::REGISTER_PROPERTIES];
    }

    private function hasProperty($property): bool
    {
        $register = in_array($property, $this->config[self::REGISTER_PROPERTIES]);
        $credential = in_array($property, $this->config[self::CREDENTIAL_PROPERTIES]);
        return $register || $credential;
    }

    /**
     * @throws PropertyNotExistsException
     */
    public function mapValues(UserInterface $user, array $properties)
    {
        $propertyAccessor = new PropertyAccessor();
        foreach ($properties as $property => $value) {
            if ($this->hasProperty($property) && $propertyAccessor->isWritable($user, $property)) {
                $value = $properties[$property];
                $propertyAccessor->setValue($user, $property, $value);
            } else {
                throw new PropertyNotExistsException(sprintf('Error while setting property "%s"', $property));
            }
        }
    }
}
