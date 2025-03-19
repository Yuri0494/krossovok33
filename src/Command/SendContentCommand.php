<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\TelegramBot\TelegramBot;

#[AsCommand(
    name: 'app:send-content',
    description: 'send content to tg chats',
    hidden: false,
    aliases: ['app:send-content']
)]
class SendContentCommand extends Command
{
    public function __construct(
        private TelegramBot $tgBot
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}