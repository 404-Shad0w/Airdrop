<?php

namespace shadow;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\Furnace;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\particle\HugeExplodeParticle;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\ExplodeSound;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use shadow\manager\Manager;
use shadow\utils\Messages;

class Events implements Listener {

    public function handlePlace(BlockPlaceEvent $event): void
    {
        $item = $event->getItem();

        if (!$item->getNamedTag()->getTag('airdrop_item') ||
            $item->getNamedTag()->getString('airdrop_item') !== 'AIRDROP') {
            return;
        }

        $player = $event->getPlayer();
        $blockPlaced = $event->getBlockAgainst(); // ← corrección aquí
        $chestPos = $blockPlaced->getPosition()->up(1);
        $world = $player->getWorld();

        $airdropItems = Loader::getInstance()->getManager()->getAirdropItems();
        if ($airdropItems === null) {
            $player->sendMessage(TextFormat::RED . 'No se pudo colocar el airdrop, avisa a un staff o owner para que solucione el problema');
            return;
        }

        $world->setBlock($chestPos, VanillaBlocks::CHEST());
        $world->addSound($chestPos, new ExplodeSound());
        $world->addParticle($chestPos, new ExplodeParticle());

        $tile = $world->getTile($chestPos);
        if (!($tile instanceof Chest)) {
            $player->sendMessage(Messages::AIRDROP_NOT_FOUND);
            return;
        }

        $inventory = $tile->getInventory();
        $items = [];
        for ($i = 0; $i < 27; $i++) {
            $randomItem = Loader::getInstance()->getManager()->getRandomAirdropItem();
            if ($randomItem !== null) {
                $items[$i] = $randomItem;
            }
        }
        $inventory->setContents($items);
        $tile->setName('§l§3Airdrop');

        $item->pop();
        $player->getInventory()->setItemInHand($item);

        $TextPosition = $chestPos->add(0.5, 1.5, 0.5);
        $floatingText = new FloatingTextParticle('§l§3Airdrop', '');
        $world->addParticle($TextPosition, $floatingText);

        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($world, $TextPosition, $floatingText): void {
                $floatingText->setText('');
                $floatingText->setTitle('');
                $world->addParticle($TextPosition, $floatingText);
            }
        ), 120);

        $event->cancel();
    }
}