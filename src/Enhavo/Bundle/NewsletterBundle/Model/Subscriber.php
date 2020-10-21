<?php
/**
 * Subscriber.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

class Subscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $subscription;

    /**
     * @var string|null
     */
    private $confirmationToken;

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     */
    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    /**
     * @param string|null $subscription
     */
    public function setSubscription(?string $subscription): void
    {
        $this->subscription = $subscription;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $token
     */
    public function setConfirmationToken(?string $token): void
    {
        $this->confirmationToken = $token;
    }

    public function __toString()
    {
        return $this->email;
    }
}
