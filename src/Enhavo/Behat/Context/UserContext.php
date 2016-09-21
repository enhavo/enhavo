<?php

namespace Enhavo\Behat\Context;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Behat\Gherkin\Node\TableNode;
use Enhavo\Bundle\UserBundle\Entity\User;
use Enhavo\Bundle\UserBundle\Entity\Group;

/**
 * Defines application features from the specific context.
 */
class UserContext extends KernelContext
{
    /**
     * @Given following users
     */
    public function followingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $user = $this->findOrCreateUser($data['username'], $data['email']);
            if(array_key_exists('enabled', $data)) {
                $user->setEnabled($data['enabled']);
            }
            if(array_key_exists('password', $data)) {
                $user->setPlainPassword($data['password']);
                $this->getContainer()->get('fos_user.user_manager')->updatePassword($user);
            }
        }
        $this->getManager()->flush();
    }

    /**
     * @Given admin user
     */
    public function adminUser()
    {
        $user = $this->findUser('admin');
        if($user === null) {
            $group = $this->createAdminGroup();
            $user = $this->createAdminUser($group);
            $user->addRole('ROLE_SUPER_ADMIN');
            $em = $this->getManager();
            $em->persist($group);
            $em->persist($user);
            $em->flush();
        }
    }

    /**
     * @param Group $group
     * @return User
     */
    public function createAdminUser($group)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@enhavo.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->addGroup($group);
        return $user;
    }

    /**
     * @return Group
     */
    public function createAdminGroup()
    {
        $roles = $this->get('security.roles.provider')->getRoles();
        $group = new Group();

        foreach($roles as $role => $value) {
            if(preg_match('/esperanto/i', $role)) {
                $group->addRole($role);
            }
        }

        $group->setName('Admin');
        return $group;
    }


    protected function findOrCreateUser($username, $email)
    {
        $user = $this->get('enhavo_user.repository.user')->findOneBy([
            'username' => $username
        ]);

        if($user === null) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $this->getManager()->persist($user);
        }

        return $user;
    }

    protected function findUser($username)
    {
        return $this->get('enhavo_user.repository.user')->findOneBy([
            'username' => $username
        ]);
    }

    /**
     * @Given /^I am logged in as user "([^""]*)"$/
     */
    public function iAmLoggedInAsUser($username)
    {
        $user = $this->findUser($username);
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'administration', $user->getRoles());

        $session = $this->get('session');
        $session->set('_security_user', serialize($token));
        $session->save();

        $this->getSession()->setCookie($session->getName(), $session->getId());
        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * @Given /^I am logged in as admin$/
     */
    public function iAmLoggedInAsAdmin()
    {
        $this->iAmLoggedInAsUser('admin');
    }

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        $this->getSession()->restart();
    }
}