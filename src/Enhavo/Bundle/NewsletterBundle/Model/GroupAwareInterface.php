<?php
/**
 * GroupAwareInterface.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;


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
     * @return array
     */
    public function getGroups();
}
