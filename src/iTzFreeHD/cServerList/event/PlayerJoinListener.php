<?php


namespace iTzFreeHD\cServerList\event;


use iTzFreeHD\cServerList\ConsoleServerList;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PlayerJoinListener implements Listener
{

    public function event(PlayerJoinEvent $event)
    {
        ConsoleServerList::getInstance()->getScheduler()->scheduleDelayedTask(new class($event->getPlayer()) extends Task {
            private $player;

            public function __construct(Player $player)
            {
                $this->player = $player;
            }

            public function onRun(int $currentTick)
            {
                ConsoleServerList::getInstance()->openGui($this->player);
            }
        }, 20);
    }

}