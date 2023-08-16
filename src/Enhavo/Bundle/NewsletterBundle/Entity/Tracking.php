<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.08.19
 * Time: 17:27
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Tracking implements ResourceInterface
{
    const TRACKING_OPEN = 'open';
    const TRACKING_BOUNCE = 'bounce';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Receiver $receiver
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
        $this->subscriber = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return Receiver
     */
    public function getReceiver(): Receiver
    {
        return $this->receiver;
    }

    /**
     * @param Receiver $receiver
     */
    public function setReceiver(Receiver $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
