<?php
/**
 * GroupManager.php
 *
 * @since 05/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Group;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;

class GroupManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * GroupManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createGroups()
    {
        //@todo: implement groups
    }

    /**
     * @param array $codes
     * @return Group[]
     */
    public function getGroupsByCodes(array $codes)
    {
        $groups = [];
        foreach($codes as $code) {
            $group = $this->getGroupByCode($code);
            $groups[] = $group;
        }
        return $groups;
    }

    public function getGroupByCode($code)
    {
        return $this->em->getRepository('EnhavoNewsletterBundle:Group')->findOneBy(['code' => $code]);
    }
}