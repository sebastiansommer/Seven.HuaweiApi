<?php

namespace Seven\HuaweiApi\Command;

use HSPDev\HuaweiApi\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModemStatusCommand extends Command {

    protected function configure() {
        $this->setName('modem-status');
        $this->setDescription('Gets the status of the modem');

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

        $io = new SymfonyStyle($input, $output);

        $status = (array)$router->getStatus();

        if ($input->getOption('format') === 'console') {
            foreach ($status as $key => $value) {
                $output->writeln($key . ': ' . $value);
            }
        }

        if ($input->getOption('format') === 'json') {
            $io->write(\json_encode($status));
        }
    }
}