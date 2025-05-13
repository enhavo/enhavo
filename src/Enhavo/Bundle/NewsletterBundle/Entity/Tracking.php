<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Tracking
{
    public const TRACKING_OPEN = 'open';
    public const TRACKING_BOUNCE = 'bounce';

    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var string
     */
    private $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriber = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getReceiver(): Receiver
    {
        return $this->receiver;
    }

    public function setReceiver(Receiver $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
