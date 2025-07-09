<?php

namespace shadow\manager;

use pocketmine\utils\Config;
use shadow\Loader;

class Manager
{
    private array $aidrops = [];
    private Config $config;

    public function __construct()
    {
        $this->config = new Config(Loader::getInstance()->getDataFolder(). "airdrop.json", Config::JSON);
        $this->load();
    }

    public function load()
    {
        if ($this->config->exists("items")) {
            $this->aidrops = $this->config->get("items");
        } else {
            $this->aidrops = [];
        }
    }

    public function setAirdropItems(array $items)
    {
        $this->aidrops = $items;
    }

    public function getAirdropItems(): array
    {
        return $this->aidrops;
    }

    public function getRandomAirdropItems()
    {
        if (empty($this->aidrops)) return null;

        return array_rand($this->aidrops);
    }

    public function clearAirdropItems()
    {
        $this->aidrops = [];
    }

    public function save()
    {
        $this->config->set("items", $this->aidrops);
        $this->config->save();
    }
}