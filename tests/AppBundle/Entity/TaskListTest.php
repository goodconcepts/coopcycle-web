<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\TaskList;
use AppBundle\Entity\ApiUser;

class TaskListTest extends TaskCollectionTest
{
    public function setUp()
    {
        $user = new ApiUser();

        $this->taskCollection = new TaskList();
        $this->taskCollection->setCourier($user);
    }

    public function testAddRemoveTaskChangesAssignedUser()
    {
        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();

        $taskList->addTask($task);
        $this->assertSame($user, $task->getAssignedCourier());

        $taskList->removeTask($task);
        $this->assertNull($task->getAssignedCourier());
    }

    public function testUpdateTasksInsertTask()
    {
        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();
        $task->setId(1);
        $task1 = new Task();
        $task1->setId(2);
        $newTask = new Task();
        $newTask->setId(3);

        $taskList->addTask($task, 0);
        $taskList->addTask($task1, 1);

        $tasksToAssign = new \SplObjectStorage();
        $tasksToAssign[$task] = 0;
        $tasksToAssign[$newTask] = 1;
        $tasksToAssign[$task1] = 2;
        $taskList->updateTasks($tasksToAssign);

        $this->assertEquals($taskList->findAt(0)->getTask()->getId(), 1);
        $this->assertEquals($taskList->findAt(1)->getTask()->getId(), 3);
        $this->assertEquals($taskList->findAt(2)->getTask()->getId(), 2);
    }

    public function testUpdateTasksPushTask()
    {
        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();
        $task->setId(1);
        $task1 = new Task();
        $task1->setId(2);
        $newTask = new Task();
        $newTask->setId(3);

        $taskList->addTask($task, 0);
        $taskList->addTask($task1, 1);

        $tasksToAssign = new \SplObjectStorage();
        $tasksToAssign[$task] = 0;
        $tasksToAssign[$task1] = 1;
        $tasksToAssign[$newTask] = 2;
        $taskList->updateTasks($tasksToAssign);

        $this->assertEquals($taskList->findAt(0)->getTask()->getId(), 1);
        $this->assertEquals($taskList->findAt(1)->getTask()->getId(), 2);
        $this->assertEquals($taskList->findAt(2)->getTask()->getId(), 3);
    }

    public function testUpdateTasksRemoveTask()
    {

        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();
        $task->setId(1);
        $task1 = new Task();
        $task1->setId(2);
        $toRemove = new Task();
        $toRemove->setId(3);

        $taskList->addTask($task, 0);
        $taskList->addTask($task1, 1);
        $taskList->addTask($toRemove, 2);

        $tasksToAssign = new \SplObjectStorage();
        $tasksToAssign[$task] = 0;
        $tasksToAssign[$task1] = 1;
        $taskList->updateTasks($tasksToAssign);

        $this->assertEquals($taskList->findAt(0)->getTask()->getId(), 1);
        $this->assertEquals($taskList->findAt(1)->getTask()->getId(), 2);
        $this->assertEquals($taskList->findAt(2), null);

    }

    public function testUpdateTasksRemoveTaskNotLast()
    {

        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();
        $task->setId(1);
        $task1 = new Task();
        $task1->setId(2);
        $toRemove = new Task();
        $toRemove->setId(3);

        $taskList->addTask($task, 0);
        $taskList->addTask($toRemove, 1);
        $taskList->addTask($task1, 2);

        $tasksToAssign = new \SplObjectStorage();
        $tasksToAssign[$task] = 0;
        $tasksToAssign[$task1] = 1;
        $taskList->updateTasks($tasksToAssign);

        $this->assertEquals($taskList->findAt(0)->getTask()->getId(), 1);
        $this->assertEquals($taskList->findAt(1)->getTask()->getId(), 2);
        $this->assertEquals($taskList->findAt(2), null);

    }

    public function testUpdateTasksReorderTasks()
    {

        $user = new ApiUser();

        $taskList = new TaskList();
        $taskList->setCourier($user);
        $taskList->setDate(new \DateTime());

        $task = new Task();
        $task->setId(1);
        $task1 = new Task();
        $task1->setId(2);
        $task2 = new Task();
        $task2->setId(3);

        $taskList->addTask($task, 0);
        $taskList->addTask($task1, 1);
        $taskList->addTask($task2, 2);

        $tasksToAssign = new \SplObjectStorage();
        $tasksToAssign[$task] = 0;
        $tasksToAssign[$task1] = 2;
        $tasksToAssign[$task2] = 1;
        $taskList->updateTasks($tasksToAssign);

        $this->assertEquals($taskList->findAt(0)->getTask()->getId(), 1);
        $this->assertEquals($taskList->findAt(1)->getTask()->getId(), 3);
        $this->assertEquals($taskList->findAt(2)->getTask()->getId(), 2);

    }
}
