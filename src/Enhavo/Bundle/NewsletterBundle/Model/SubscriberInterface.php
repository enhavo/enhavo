<?php
/**
 * SubscriberInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;


interface SubscriberInterface
{
    /**
     * Set email
     *
     * @param string $email
     *
     * @return SubscriberInterface
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return SubscriberInterface
     */
    public function setActive($active);

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive();

    /**
     * Set token
     *
     * @param string $token
     *
     * @return SubscriberInterface
     */
    public function setToken($token);

    /**
     * Get token
     *
     * @return string
     */
    public function getToken();

    /**
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     * @return SubscriberInterface
     */
    public function setActivatedAt($activatedAt);

    /**
     * Get activatedAt
     *
     * @return \DateTime
     */
    public function getActivatedAt();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @return boolean
     */
    public function isActive();
}