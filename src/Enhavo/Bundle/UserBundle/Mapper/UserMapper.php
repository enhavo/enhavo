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
    /** @var array */
    private $config;

    /**
     * UserMapper constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }


    public function getCredentialProperties(): array
    {
        return $this->config['credential_properties'];
    }

    public function setUsername(UserInterface $user, array $credentials)
    {
        $user->setUsername($this->getUsername($credentials));
    }

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
        return $this->config['register_properties'];
    }

    public function setRegisterValues(UserInterface $user, array $values)
    {
        $propertyAccessor = new PropertyAccessor();
        $properties = $this->getCredentialProperties();
        foreach ($properties as $property) {
            if (isset($values[$property]) && $propertyAccessor->isWritable($user, $property)) {
                $value = $values[$property];
                $propertyAccessor->setValue($user, $property, $value);
            } else {
                throw new PropertyNotExistsException(sprintf('Error while setting property "%s"', $property));
            }
        }
    }
}
