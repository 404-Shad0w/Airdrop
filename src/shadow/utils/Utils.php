<?php

namespace shadow\utils;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use shadow\Loader;

class Utils
{
    public const AIRDROP_NAMEFORMAT = '§l§3Airdrop';

    public static function giveAirdrop(Player $player, int $count = 1): void
    {
        $airdropItems = Loader::getInstance()->getManager()->getAirdropItems();

        if (empty($airdropItems)) {
            $player->sendMessage(Messages::NO_AIRDROP_ITEMS);
            return;
        }

        $itemNames = [];

        foreach ($airdropItems as $item) {
            if ($item instanceof Item && $item->getName() !== '') {
                $itemNames[] = TextFormat::colorize('§f• §7' . trim($item->getName()));
            }
        }

        $airdrop = VanillaBlocks::CHEST()->asItem();
        $airdrop->setCustomName(self::AIRDROP_NAMEFORMAT);
        $airdrop->setCount($count);
        $airdrop->setLore([
            '§7§oAirdrop Items:',
            ...$itemNames,
            '',
            '§e» Click derecho para abrir',
        ]);
        $airdrop->getNamedTag()->setString('airdrop_item', 'AIRDROP');

        $player->getInventory()->addItem($airdrop);

        $msg = str_replace('{player}', $player->getName(), Messages::GIVE_AIRDROP_ITEM);
        $player->sendMessage(TextFormat::colorize($msg));
    }
}
