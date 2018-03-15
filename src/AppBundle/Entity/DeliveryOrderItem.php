<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Order\Model\OrderItemInterface;

/**
 * @ORM\Entity
 */
class DeliveryOrderItem
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Order\Model\OrderItem")
     */
    protected $orderItem;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Delivery")
     */
    protected $delivery;

    public function __construct(OrderItemInterface $orderItem = null, Delivery $delivery = null)
    {
        $this->orderItem = $orderItem;
        $this->delivery = $delivery;
    }

    public function getOrderItem()
    {
        return $this->orderItem;
    }
}
