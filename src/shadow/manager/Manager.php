<?php

namespace shadow\manager;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use shadow\Loader;
use shadow\utils\Messages;
use shadow\utils\Serialize;

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
        $this->aidrops = [];
        $raw = $this->config->get('items', []);
        foreach ($raw as $itemData) {
            $this->aidrops[] = Serialize::deserialize($itemData);
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

    public function getRandomAirdropItem(): ?Item
    {
        if (empty($this->aidrops)) return null;

        $key = array_rand($this->aidrops);
        return clone $this->aidrops[$key];
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
            $player->sendMessage(Messages::EDIT_AIRDROP_ITEMS);
        });
        $menu->send($player, TextFormat::colorize('&3Airdrop Loot'));
    }


    public function save(): void
    {
        $data = [];
        foreach ($this->aidrops as $item) {
            $data[] = Serialize::serialize($item);
        }
        $this->config->set('items', $data);
        $this->config->save();
    }
}