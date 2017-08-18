<?php
/**
 * UserFixture.php
 *
 * @since 05/05/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\UserBundle\Entity\User;

class UserFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $user = new User();
        $user->setEmail($args['email']);
        $user->setUsername($args['email']);
        $user->setPlainPassword($args['password']);
        
        if(isset($args['roles']) && is_array($args['roles'])) {
            foreach($args['roles'] as $role) {
                $user->addRole($role);
            }
        }

        $this->container->get('fos_user.user_manager')->updateUser($user, false);

        return $user;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 1;
    }
}