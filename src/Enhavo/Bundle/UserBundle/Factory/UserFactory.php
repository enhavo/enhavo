<?php
/**
 * @author blutze-media
 * @since 2020-10-27
 */

namespace Enhavo\Bundle\UserBundle\Factory;


use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\UserBundle\Model\UserInterface;

class UserFactory extends Factory
{


    /**
     * Creates a user and returns it.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param bool   $active
     * @param bool   $superadmin
     *
     * @return UserInterface
     */
    public function create($username, $password, $email, $active, $superadmin)
    {
        $user = $this->createNew();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((bool) $active);
        $user->setSuperAdmin((bool) $superadmin);

        return $user;
    }
}
