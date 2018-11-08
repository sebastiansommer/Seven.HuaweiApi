<?php

namespace Seven\HuaweiApi\Command;

use HSPDev\HuaweiApi\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModemRestartCommand extends Command {

    protected function configure() {
        $this->setName('modem-restart');
        $this->setDescription('Restarts the modem');

        $this->addArgument('ip', InputArgument::OPTIONAL, 'Router IP', '192.168.8.1');
        $this->addArgument('username', InputArgument::OPTIONAL, 'Username', 'admin');
        $this->addArgument('password', InputArgument::OPTIONAL, 'Password', 'admin');
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

        $io->title('Connected to: ' . $input->getArgument('ip'));
        $io->listing([
            'IP: ' . $input->getArgument('ip'),
            'Username: ' . $input->getArgument('username'),
            'Password: ' . $input->getArgument('password')
        ]);

        $io->writeln('<info>Disconnecting...</info>');
        $io->writeln('Waiting for 20 sec');
        $router->setDataSwitch(0);
        sleep(20);

        $io->writeln('<info>Connecting...</info>');
        $output->writeln('Waiting for 15 sec');
        $router->setDataSwitch(1);
        sleep(15);

        $io->writeln('<info>Modem restarted successfully.</info>');
    }
}