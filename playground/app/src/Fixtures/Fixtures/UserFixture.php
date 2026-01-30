<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\UserBundle\Model\User;

/**
 * @author gseidel
 */
class UserFixture extends AbstractFixture
{
    public function create($args)
    {
        $user = new User(); // todo use factory
        $user->setEmail($args['email']);
        $user->setUsername($args['email']);
        $user->setPlainPassword($args['password']);

        if (isset($args['roles']) && is_array($args['roles'])) {
            foreach ($args['roles'] as $role) {
                $user->addRole($role);
            }
        }

        $this->container->get('fos_user.user_manager')->updateUser($user, false);

        return $user;
    }

    public function getName()
    {
        return 'User';
    }

    public function getOrder()
    {
        return 1;
    }
}
