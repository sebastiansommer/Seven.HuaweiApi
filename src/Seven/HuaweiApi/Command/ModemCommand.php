<?php

namespace Seven\HuaweiApi\Command;

use HSPDev\HuaweiApi\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModemCommand extends Command {

    protected function configure() {
        $this->setName('restart');
        $this->setDescription('Restarts the modem');

        $this->addArgument('ip', InputArgument::OPTIONAL, 'Router IP');
        $this->addArgument('username', InputArgument::OPTIONAL, 'Username');
        $this->addArgument('password', InputArgument::OPTIONAL, 'Password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $ip = $input->getArgument('ip') ? $input->getArgument('ip') : '192.168.8.1';
        $username = $input->getArgument('username') ? $input->getArgument('username') : 'admin';
        $password = $input->getArgument('password') ? $input->getArgument('password') : 'admin';

        $router = new Router();
        $router->setAddress($ip);
        $router->login($username, $password);

        $io = new SymfonyStyle($input, $output);

        $io->title('Connected to: ' . $ip);
        $io->listing([
            'IP: ' . $ip,
            'Username: ' . $username,
            'Password: ' . $password
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