<?php
/**
 * @author blutze-media
 * @since 2020-10-29
 */

namespace Enhavo\Bundle\UserBundle\Mapper;


use Enhavo\Bundle\UserBundle\Model\UserInterface;

interface UserMapperInterface
{
    public function getCredentialProperties(): array;

    public function setUsername(UserInterface $user, array $credentials);

    public function getUsername(array $credentials): string;

    public function getRegisterProperties(): array;

    public function setRegisterValues(UserInterface $user, array $properties);

}
