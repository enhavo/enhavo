<?php
/**
 * UserRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package Enhavo\Bundle\UserBundle\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function findByTerm($term)
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.username LIKE :term')
            ->orWhere('t.firstName LIKE :term')
            ->orWhere('t.lastName LIKE :term')
            ->orWhere('t.email LIKE :term')
            ->setParameter('term', sprintf('%s%%', $term))
            ->orderBy('t.username');

        $paginator = $this->getPaginator($query);
        return $paginator;
    }


    /**
     * @param $token
     * @return UserInterface|object|null
     */
    public function findByConfirmationToken($token): ?UserInterface
    {
        return $this->findOneBy([
            'confirmationToken' => $token,
        ]);
    }

    /**
     * @param $username
     * @return UserInterface|object|null
     */
    public function findByUsername($username): ?UserInterface
    {
        return $this->findOneBy([
            'username' => $username,
        ]);
    }

    /**
     * @param $email
     * @return UserInterface|object|null
     */
    public function findByEmail($email): ?UserInterface
    {
        return $this->findOneBy([
            'email' => $email,
        ]);
    }

    public function loadUserByIdentifier($identifier): ?UserInterface
    {
        $user = $this->findByUsername($identifier);
        if ($user) {
            return $user;
        }
        $user = $this->findByEmail($identifier);
        return $user;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        // Deprecated but still required by the interface
        return $this->loadUserByIdentifier($username);
    }
}
