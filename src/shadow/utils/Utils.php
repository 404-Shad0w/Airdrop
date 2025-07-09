<?php

namespace shadow\utils;

use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use shadow\Loader;

class Utils
{
    public const AIRDROP_NAMEFORMAT = "§l§3Airdrop";

    public static function giveAirdrop(Player $player, int $count = 1): void
    {
        $itemContent = Loader::getInstance()->getManager()->getAirdropItems();

        if (empty($itemContent)) {
            $player->sendMessage(Messsges::NO_AIRDROP_ITEMS);
            return;
        }

        $airdrop = VanillaBlocks::FURNACE()->asItem();
        $airdrop->setCustomName(self::AIRDROP_NAMEFORMAT);
        $airdrop->setCount($count);
        $airdrop->setLore([
            "§7Airdrop items",
            "§7Right Click to open",
            "§7Contains: " . implode(TextFormat::colorize(", "), array_keys($itemContent)),
        ]);
        $airdrop->getNamedTag()->setString('airdrop_item', 'AIRDROP');

        $player->getInventory()->addItem($airdrop);
        $msg = str_replace("{player}", $player->getName(), Messsges::GIVE_AIRDROP_ITEM);
        $player->sendMessage(TextFormat::colorize($msg));
    }
}