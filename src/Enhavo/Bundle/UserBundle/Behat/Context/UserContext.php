<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Enhavo\Bundle\AppBundle\Behat\Context\ClientAwareContext;
use Enhavo\Bundle\AppBundle\Behat\Context\ClientAwareTrait;
use Enhavo\Bundle\AppBundle\Behat\Context\KernelAwareContext;
use Enhavo\Bundle\AppBundle\Behat\Context\ManagerAwareTrait;
use Enhavo\Bundle\UserBundle\Behat\Exception\UserLoginException;
use Enhavo\Bundle\UserBundle\Model\Group;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Defines application features from the specific context.
 */
class UserContext implements Context, ClientAwareContext, KernelAwareContext
{
    use ManagerAwareTrait;
    use ClientAwareTrait;

    /**
     * @Given following users
     */
    public function followingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $user = $this->findOrCreateUser($data['username'], $data['email']);
            $user->setEnabled(true);
            if (array_key_exists('enabled', $data)) {
                $user->setEnabled($data['enabled']);
            }
            if (array_key_exists('password', $data)) {
                $user->setPlainPassword($data['password']);
            }
            if (array_key_exists('roles', $data)) {
                $roles = explode(',', $data['roles']);
                $roles = array_map(function ($data) {
                    return trim($data);
                }, $roles);
                $user->setRoles($roles);
            }
        }
        $this->getContainer()->get('Enhavo\Bundle\UserBundle\User\UserManager')->update($user);
    }

    /**
     * @Given admin user
     */
    public function adminUser()
    {
        $user = $this->findUser('admin@enhavo.com');
        $em = $this->getManager();
        if (null === $user) {
            $group = $this->createAdminGroup();
            $user = $this->createAdminUser($group);
            $em->persist($group);
        }
        $user->setEnabled(true);
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $this->get('Enhavo\Bundle\UserBundle\User\UserManager')->update($user, true, false);
        $em->flush();
    }

    /**
     * @Given /^I am logged in as user "([^""]*)"$/
     *
     * @throws UserLoginException
     */
    public function iAmLoggedInAsUser($username)
    {
        /** @var RouterInterface $router */
        $router = $this->get(RouterInterface::class);
        $crawler = $this->getClient()->request('GET', $router->generate('enhavo_user_test_security_login', [
            'username' => $username,
        ]));
        $text = $crawler->filter('body')->text();
        if ('Ok' !== $text) {
            throw UserLoginException::invalidUser($username);
        }
    }

    /**
     * @Given /^I am logged in as admin$/
     */
    public function iAmLoggedInAsAdmin()
    {
        $this->iAmLoggedInAsUser('admin@enhavo.com');
    }

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        $this->getClient()->getCookieJar()->clear();
    }

    /**
     * @param Group $group
     *
     * @return User
     */
    private function createAdminUser($group)
    {
        $user = new User();
        $user->setEmail('admin@enhavo.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->addGroup($group);

        return $user;
    }

    /**
     * @return Group
     */
    private function createAdminGroup()
    {
        $roles = $this->get('security.roles.provider')->getRoles();
        $group = new Group();

        foreach ($roles as $role => $value) {
            if (preg_match('/esperanto/i', $role)) {
                $group->addRole($role);
            }
        }

        $group->setName('Admin');

        return $group;
    }

    private function findOrCreateUser($username, $email)
    {
        $user = $this->get('enhavo_user.user.repository')->findOneBy([
            'username' => $username,
        ]);

        if (null === $user) {
            $user = new User();
            $user->setEmail($email);
            $this->get('Enhavo\Bundle\UserBundle\User\UserManager')->update($user);
        }

        return $user;
    }

    private function findUser($username): ?UserInterface
    {
        return $this->get('enhavo_user.user.repository')->findOneBy([
            'username' => $username,
        ]);
    }
}
