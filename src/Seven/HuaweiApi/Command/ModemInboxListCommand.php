<?php

namespace Seven\HuaweiApi\Command;

use HSPDev\HuaweiApi\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModemInboxListCommand extends Command {

    protected function configure() {
        $this->setName('modem-inbox-get');
        $this->setDescription('Gets the inbox of the modem');

        $this->addArgument('ip', InputArgument::OPTIONAL, 'Router IP', '192.168.8.1');
        $this->addArgument('username', InputArgument::OPTIONAL, 'Username', 'admin');
        $this->addArgument('password', InputArgument::OPTIONAL, 'Password', 'admin');
        $this->addOption('format', 'f', InputArgument::OPTIONAL, 'Format', 'console');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $router = new Router();
        $router->setAddress($input->getArgument('ip'));
        $router->login($input->getArgument('username'), $input->getArgument('password'));

        $inbox = $router->getInbox();

        $io = new SymfonyStyle($input, $output);

        if ($inbox->code) {
            $io->error($inbox->code . ' (See: https://github.com/HSPDev/Huawei-E5180-API#user-content-huawei-router-api-error-codes)');
            return;
        }

        $messages = [];

        foreach ($inbox->Messages->Message as $message) {
            $messages[] = (array)$message;
        }

        if ($input->getOption('format') === 'console') {
            $io->table([
                'Smstat',
                'Index',
                'Phone',
                'Content',
                'Date',
                'Sca',
                'SaveType',
                'Priority',
                'SmsType'
            ], $messages);
        }

        if ($input->getOption('format') === 'json') {
            $io->write(\json_encode($messages));
        }
    }
}