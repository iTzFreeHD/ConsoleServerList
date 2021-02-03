<?php


namespace iTzFreeHD\cServerList;

use iTzFreeHD\cServerList\event\PlayerJoinListener;
use iTzFreeHD\cServerList\gui\ServerListGui;
use iTzFreeHD\cServerList\query\asyncQuery;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class ConsoleServerList extends PluginBase
{

    /** @var self */
    private static $instance;

    /** @var ServerConfig */
    private $serverConfig;

    /** @var array  */
    public $serverList = [];

    public function onEnable()
    {
        self::$instance = $this;
        $this->registerEvents();
        $this->serverConfig = new ServerConfig($this->getDataFolder()."servers.json", Config::JSON);
    }

    /**
     * @return ServerConfig
     */
    public function getServerConfig(): ServerConfig
    {
        return $this->serverConfig;
    }

    public function openGui(Player $player)
    {
        $this->serverList[$player->getName()] = [];
        $name = $player->getName();
        foreach ($this->getServerConfig()->getServerList() as $key => $values) {
            $this->getServer()->getAsyncPool()->submitTask(new AsyncQuery($values["ip"], $values["port"], function ($array) use ($name, $key) {
                if (($player = Server::getInstance()->getPlayerExact($name)) == null) return;
                $instance = ConsoleServerList::getInstance();
                $instance->serverList[$player->getName()][$key] = $array;
                if (count($instance->serverList[$player->getName()]) == count($instance->getServerConfig()->getServerList())) {
                    $player->sendForm(new ServerListGui($instance->serverList[$player->getName()]));
                }
            }));
        }

    }

    private function registerEvents()
    {
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoinListener(), $this);
    }

    /**
     * @return ConsoleServerList
     */
    public static function getInstance(): ConsoleServerList
    {
        return self::$instance;
    }

}