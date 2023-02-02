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
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package Enhavo\Bundle\UserBundle\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function findByTerm($term): ?Pagerfanta
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.username LIKE :term')
            ->orWhere('t.firstName LIKE :term')
            ->orWhere('t.lastName LIKE :term')
            ->orWhere('t.email LIKE :term')
            ->setParameter('term', sprintf('%s%%', $term))
            ->orderBy('t.username');

        return $this->getPaginator($query);
    }

    public function findByConfirmationToken($token): ?UserInterface
    {
        return $this->findOneBy([
            'confirmationToken' => $token,
        ]);
    }

    public function findByUsername($username): ?UserInterface
    {
        return $this->findOneBy([
            'username' => $username,
        ]);
    }

    public function findByEmail($email): ?UserInterface
    {
        return $this->findOneBy([
            'email' => $email,
        ]);
    }

    public function loadUserByIdentifier($identifier): ?UserInterface
    {
        return $this->findOneBy([
            'userIdentifier' => $identifier,
        ]);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        // Deprecated but still required by the interface
        return $this->loadUserByIdentifier($username);
    }
}
