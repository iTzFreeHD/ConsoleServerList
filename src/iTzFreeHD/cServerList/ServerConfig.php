<?php


namespace iTzFreeHD\cServerList;


use pocketmine\utils\Config;

class ServerConfig extends Config
{

    public function __construct(string $file, int $type = Config::DETECT, array $default = [], &$correct = null)
    {
        parent::__construct($file, $type, $default, $correct);
        if ($this->exists("version")) return;
        $this->setAll([
            "version" => 1.0,
            "servers" => [
                [
                    "name" => "RusherHub",
                    "ip" => "rusherhub.net",
                    "port" => 19132,
                ],
                [
                    "name" => "SchwitzerCube",
                    "ip" => "schwitzercube.net",
                    "port" => 19132,
                ],
                [
                    "name" => "RyzerBE",
                    "ip" => "ryzer.be",
                    "port" => 19132,
                ],
                [
                    "name" => "EntenGames",
                    "ip" => "entengames.de",
                    "port" => 19132,
                ],
                [
                    "name" => "NetherGames weil Tobi sonst weint",
                    "ip" => "play.nethergames.org",
                    "port" => 19132,
                ],
                [
                    "name" => "RushNation",
                    "ip" => "rushnation.net",
                    "port" => 19132,
                ],
            ],
        ]);
        $this->save();
    }

    /**
     * @param string $k
     * @param bool $v
     */
    public function set($k, $v = true)
    {
        parent::set($k, $v);
        $this->save();
    }

    /**
     * @return array
     */
    public function getServerList() : array
    {
        return $this->get("servers", []);
    }

    /**
     * @param int $id
     */
    public function removeServer(int $id)
    {
        $servers = $this->getServerList();
        unset($servers[$id]);
        sort($servers);
        $this->set("servers", $servers);
    }

    /**
     * @param string $name
     * @param string $ip
     * @param int $port
     */
    public function addServer(string $name,string $ip,int $port)
    {
        $servers = $this->getServerList();
        $servers[] = [
            "name" => $name,
            "ip" => $ip,
            "port" => $port
        ];
        $this->set("servers", $servers);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $ip
     * @param int $port
     */
    public function editServer(int $id, string $name,string $ip,int $port)
    {
        $servers = $this->getServerList();
        $servers[$id] = [
            "name" => $name,
            "ip" => $ip,
            "port" => $port
        ];
        $this->set("servers", $servers);
    }

}