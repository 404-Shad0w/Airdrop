<?php

namespace shadow\manager;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use shadow\Loader;
use shadow\utils\Messsges;

class Manager
{
    private array $aidrops = [];
    private Config $config;

    public function __construct()
    {
        $this->config = new Config(Loader::getInstance()->getDataFolder(). "airdrop.json", Config::JSON);
        $this->load();
    }

    public function load(): void
    {
        if ($this->config->exists("items")) {
            $this->aidrops = $this->config->get("items");
        } else {
            $this->aidrops = [];
        }
    }

    public function setAirdropItems(array $items): void
    {
        $this->aidrops = $items;
        $this->save();
    }

    public function getAirdropItems(): array
    {
        return $this->aidrops;
    }

    public function getRandomAirdropItems(): ?int
    {
        if (empty($this->aidrops)) return null;

        return array_rand($this->aidrops);
    }

    public function clearAirdropItems(): void
    {
        $this->aidrops = [];
        $this->save();
    }

    public function editAirdropContent(Player $player): void
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->getInventory()->setContents($this->aidrops);
        $menu->setInventoryCloseListener(function (Player $player, Inventory $inventory): void {
            $this->aidrops = $inventory->getContents();
            $this->setAirdropItems($this->aidrops);
            $player->sendMessage(Messsges::EDIT_AIRDROP_ITEMS);
        });
        $menu->send($player, TextFormat::colorize('&3Airdrop Loot'));
    }



    public function save(): void
    {
        $this->config->set("items", $this->aidrops);
        $this->config->save();
    }
}