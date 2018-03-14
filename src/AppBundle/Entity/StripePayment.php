<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Model\TaxableTrait;
use Doctrine\Common\Util\ClassUtils;
use Ramsey\Uuid\Uuid;

class StripePayment
{
    use TaxableTrait;

    const STATUS_PENDING = 'PENDING';
    const STATUS_CAPTURED = 'CAPTURED';

    protected $id;

    /**
     * @var string
     */
    private $uuid;

    protected $user;

    protected $resourceClass;

    protected $resourceId;

    protected $status = self::STATUS_PENDING;

    protected $charge;

    public function prePersist() {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(ApiUser $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getResourceClass()
    {
        return $this->resourceClass;
    }

    public function setResourceClass($resourceClass)
    {
        $this->resourceClass = $resourceClass;

        return $this;
    }

    public function getResourceId()
    {
        return $this->resourceId;
    }

    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getCharge()
    {
        return $this->charge;
    }

    public function setCharge($charge)
    {
        $this->charge = $charge;

        return $this;
    }

    public static function create(ApiUser $user, $resource)
    {
        // TODO Throw Exception if resource has no id

        $stripePayment = new self();

        $stripePayment->setUser($user);
        $stripePayment->setResourceClass(ClassUtils::getClass($resource));
        $stripePayment->setResourceId($resource->getId());
        $stripePayment->setTotalExcludingTax($resource->getTotalExcludingTax());
        $stripePayment->setTotalTax($resource->getTotalTax());
        $stripePayment->setTotalIncludingTax($resource->getTotalIncludingTax());

        return $stripePayment;
    }
}
