<?php
/**
 * GroupAwareInterface.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface GroupAwareInterface
{
    /**
     * @param GroupInterface $group
     */
    public function addGroup(GroupInterface $group): void;

    /**
     * @param GroupInterface $group
     */
    public function removeGroup(GroupInterface $group): void;

    /**
     * @return array|Collection|ArrayCollection
     */
    public function getGroups();
}
