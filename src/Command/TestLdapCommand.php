<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Ldap\Ldap;

class TestLdapCommand extends Command
{
    protected static $defaultName = 'test-ldap';

    protected function configure()
    {
        $this
            ->setDescription('Frogatu ldap konexioa')
            ->addArgument('ip', InputArgument::REQUIRED, 'LDAP zerbitzariaren IP-a')
            ->addArgument('username', InputArgument::REQUIRED, 'LDAP zerbitzariaren username')
            ->addArgument('password', InputArgument::REQUIRED, 'LDAP zerbitzariaren password')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ip = $input->getArgument('ip');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        if ($ip) {
            $io->note(sprintf('Zerbitzaria: %s', $ip));
            $io->note(sprintf('Erabiltzailea: %s', $username));
            $io->note(sprintf('Pasahitza: %s', $password));

            $srv = "ldap://$ip:389";
            $io->note( sprintf( $srv) );
            $ldap = Ldap::create('ext_ldap', ['connection_string' => $srv]);
            $ldap->bind("CN=$username,CN=Users,DC=pasaia,DC=net", "$password");


        }



        $io->success('ZORIONAK hiiiiiiiiiii!');

        return 0;
    }
}
