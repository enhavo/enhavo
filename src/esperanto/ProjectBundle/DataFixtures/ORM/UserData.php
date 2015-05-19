<?php
/**
 * UserData.php
 *
 * @since 15/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use esperanto\UserBundle\Entity\User;
use esperanto\UserBundle\Entity\Group;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->addUser('admin', 'admin', 'admin@esperanto-agentur.com');

        $adminGroup = $this->addAdminGroup();
        $user->addGroup($adminGroup);

        $editorialGroup = $this->addEditorGroup();

        $manager->persist($user);
        $manager->persist($adminGroup);
        $manager->persist($editorialGroup);
        $manager->flush();
    }

    /**
     * @param $name
     * @param $passwd
     * @param $email
     * @return User
     */
    public function addUser($name, $passwd, $email)
    {
        $user = new User();
        $user->setUsername($name);
        $user->setEmail($email);
        $user->setPlainPassword($passwd);
        $user->setEnabled(true);

        return $user;
    }

    public function addAdminGroup() {
        $roles = $this->container->get('security.roles.provider')->getRoles();
        $group = new Group();

        foreach($roles as $role => $value) {
            if(preg_match('/esperanto/i', $role)) {
                $group->addRole($role);
            }
        }

        $group->setName('Administratoren');

        return $group;
    }

    public function addEditorGroup() {
        $roles = $this->container->get('security.roles.provider')->getRoles();
        $group = new Group();

        foreach($roles as $role => $value) {

            if(preg_match('/ROLE_ESPERANTO_USER_ADMIN_USER/i', $role)) continue;
            if(preg_match('/ROLE_ESPERANTO_USER_ADMIN_GROUP/i', $role)) continue;
            if(preg_match('/esperanto/i', $role)) {
                $group->addRole($role);
            }
        }

        $group->setName('Redakteure');

        return $group;
    }
} 