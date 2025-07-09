<?php

namespace shadow\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class GiveCommand extends Command
{

    public function __construct()
    {
        parent::__construct('airdropgive', 'Gives an airdrop to a player', 'Usage: /give <player> <item> [amount]');
        $this->setPermission('airdropgive.cmd');
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $player, string $label, array $args)
    {
    }
}