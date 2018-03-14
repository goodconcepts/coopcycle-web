<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Task\CollectionTrait as TaskCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A TaskCollection is the database representation of a Task\CollectionInterface.
 * It uses Doctrine's Inheritance Mapping to implement a OneToMany relationship with TaskCollectionItem.
 * There are two concrete implementations of TaskCollection: Delivery & TaskList.
 *
 * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/inheritance-mapping.html
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "delivery"  = "AppBundle\Entity\Delivery",
 *   "task_list" = "AppBundle\Entity\TaskList"
 * })
 */
abstract class TaskCollection
{
    use TaskCollectionTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TaskCollectionItem", mappedBy="parent", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @Groups({"task_collection", "task"})
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Mandatory for serialization to work.
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addTask(Task $task, $position = null)
    {
        $item = null;
        foreach ($this->items as $i) {
            if ($i->getTask() === $task) {
                $item = $i;
                break;
            }
        }

        if ($item === null) {
            $item = new TaskCollectionItem();
            $item->setTask($task);
            $item->setParent($this);

            $this->items->add($item);
        }

        $item->setPosition($position !== null ? $position : -1);

        return $this;
    }

    public function removeTask(Task $task)
    {
        foreach ($this->items as $item) {
            if ($item->getTask() === $task) {
                $this->items->removeElement($item);
                $item->setParent(null);
                break;
            }
        }
    }

    public function getTasks()
    {
        return $this->items->map(function (TaskCollectionItem $item) {
            return $item->getTask();
        })->toArray();
    }

    public function containsTask(Task $task)
    {
        foreach ($this->getTasks() as $t) {
            if ($task === $t) {
                return true;
            }
        }

        return false;
    }

    public function findAt($position) {
        /*
         * Find item at position or return null
         */

        return $this->getItems()->filter(function (TaskCollectionItem $item) use ($position) {
            return $item->getPosition() === $position;
        })->first();
    }
}
