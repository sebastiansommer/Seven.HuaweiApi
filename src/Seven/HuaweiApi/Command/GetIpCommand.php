<?php

namespace Seven\HuaweiApi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetIpCommand extends Command {

    protected function configure() {
        $this->setName('get-ip');
        $this->setDescription('Gets the current ip');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $ip = \trim(\file_get_contents('https://ip.7sg.site/get-ip.php'));

        $output->writeln($ip);
    }
}