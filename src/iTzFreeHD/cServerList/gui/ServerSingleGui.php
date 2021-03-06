<?php


namespace iTzFreeHD\cServerList\gui;


use formBridge\form\Form;
use formBridge\form\MenuForm;
use formBridge\form\MenuOption;
use iTzFreeHD\cServerList\ConsoleServerList;
use pocketmine\Player;
use pocketmine\utils\TextFormat as c;

class ServerSingleGui extends MenuForm
{

    /** @var array */
    private $serverInfo;

    /** @var int */
    private $id;

    public function __construct(int $id,array $serverInfo)
    {
        $this->serverInfo = $serverInfo;
        $this->id = $id;
        parent::__construct("Server info", c::GRAY."Ip: ".c::WHITE.$serverInfo["ip"]."\n".c::GRAY."Port: ".c::WHITE.$serverInfo["port"]."\n".c::GRAY."Ping: ".c::WHITE.$serverInfo["ping"]."ms\n"."\n\n\n", [
            new MenuOption(c::GREEN."Join"),
            new MenuOption(c::YELLOW."Edit"),
            new MenuOption(c::RED."Remove"),
            new MenuOption(c::RED."Back"),
        ]);
    }

    public function onSubmit(Player $player): ?Form
    {
        switch ($this->getSelectedOptionIndex()) {
            case 0:
                $player->transfer($this->serverInfo["ip"], $this->serverInfo["port"]);
                break;
            case 1:
                try {
                    $serverListInfo = ConsoleServerList::getInstance()->getServerConfig()->getServerList()[$this->id];
                    $player->sendForm(new ServerEditGui($this->id, $serverListInfo["name"], $this->serverInfo["ip"], $this->serverInfo["port"]));
                } catch (\Throwable $exception) {}

                break;
            case 2:
                ConsoleServerList::getInstance()->getServerConfig()->removeServer($this->id);
                ConsoleServerList::getInstance()->openGui($player);
                break;
            case 3:
                ConsoleServerList::getInstance()->openGui($player);
                break;
        }
        return parent::onSubmit($player);
    }

    public function onClose(Player $player): ?Form
    {
        ConsoleServerList::getInstance()->openGui($player);
        return parent::onClose($player); // TODO: Change the autogenerated stub
    }

}