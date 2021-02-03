<?php


namespace iTzFreeHD\cServerList\query;


use libpmquery\PMQuery;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

class asyncQuery extends AsyncTask
{

    /** @var string */
    private $ip;

    /** @var int */
    private $port;

    /**
     * @var \Closure
     */
    private $action;

    public function __construct(string $ip,int $port,\Closure $action)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->action = $action;
    }

    public function onRun()
    {
        $ping = microtime(true);
        try {
            $query = PMQuery::query($this->ip, $this->port, 1);
            $ping = microtime(true) - $ping;
        } catch (\Exception $exception) {
            //MainLogger::getLogger()->logException($exception);
            $query = [
                'GameName' => null,
                'HostName' => null,
                'Protocol' => null,
                'Version' => null,
                'Players' => null,
                'MaxPlayers' => null,
                'ServerId' => null,
                'Map' => null,
                'GameMode' => null,
                'NintendoLimited' => null,
                'IPv4Port' => null,
                'IPv6Port' => null,
                'Extra' => null,
            ];
        }
        $query["ip"] = $this->ip;
        $query["port"] = $this->port;
        $query["ping"] = round($ping*1000);
        $this->setResult((array) $query);
    }

    public function onCompletion(Server $server)
    {
        try {
            $closure = $this->action;
            $closure((array)$this->getResult());
            parent::onCompletion($server);
        } catch (\Throwable $exception) {
            MainLogger::getLogger()->logException($exception);
        }
    }
}