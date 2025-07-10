<?php

namespace shadow\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use shadow\Loader;

class EditCommand extends Command
{

    public function __construct()
    {
        parent::__construct('airdropedit', 'Edit airdrop items', 'Usage: /airdropedit');
        $this->setPermission('airdropedit.perm');
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $player, string $label, array $args)
    {
        if (!$player instanceof Player)return;

        Loader::getInstance()->getManager()->editAirdropContent($player);
    }
}