<?php
/**
 * UserData.php
 *
 * @since 15/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace HowToVideo\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use esperanto\UserBundle\Entity\User;

class UserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->addUser('admin', 'admin', 'admin@esperanto-agentur.com'));
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
} 