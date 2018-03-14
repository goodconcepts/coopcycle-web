<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Order\Model\OrderInterface;

/**
 * @ORM\Entity
 */
class DeliveryOrder
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Order\Model\Order")
     */
    protected $order;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ApiUser")
     */
    protected $user;

    public function __construct(OrderInterface $order = null, ApiUser $user = null)
    {
        $this->order = $order;
        $this->user = $user;
    }
}
