<?php

namespace shadow\utils;

use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use shadow\Loader;

class Utils
{
    public const AIRDROP_NAMEFORMAT = "§l§3Airdrop";

    public static function giveAirdrop(Player $player)
    {
        $itemContent = Loader::getInstance()->getManager()->getAirdropItems();

        if (empty($itemContent)) {
            $player->sendMessage(Messsges::NO_AIRDROP_ITEMS);
            return;
        }



        $airdrop = VanillaBlocks::FURNACE()->asItem();
        $airdrop->setCustomName(self::AIRDROP_NAMEFORMAT);
        $airdrop->setLore([
            "§7Airdrop items",
            "§7Right Click to open",
            "§7Contains: " . implode(", ", array_keys($itemContent)),
        ]);
    }
}