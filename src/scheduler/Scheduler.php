<?php

namespace pocketmine\scheduler;

use pocketmine\Server;
use pocketmine\scheduler\Task;

class Scheduler
{
    public static function scheduleRepeatingTask(Task $task, int $interval)
    {
        Server::getInstance()->getScheduler()->scheduleRepeatingTask($task, $interval);
    }
}
