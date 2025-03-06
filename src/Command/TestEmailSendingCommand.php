<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test:email',
    description: 'Test email sending',
)]
class TestEmailSendingCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('from', 'f', InputOption::VALUE_REQUIRED, 'Email from address.')
            ->addOption('to', 't', InputOption::VALUE_REQUIRED, 'Comma-separated list of email addresses to send to.')
            ->addOption('subject', 's', InputOption::VALUE_REQUIRED, 'Email subject.')
            ->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'Email message.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendMessage(
            $input->getOption('from'),
            $input->getOption('to'),
            $input->getOption('subject'),
            $input->getOption('message')
        );
        $io->success('Email sent.');

        return Command::SUCCESS;
    }

    /**
     * Send message.
     *
     * @param string $subject
     * @param string $message
     *
     * @return null
     */
    protected function sendMessage($from, $to, $subject, $message)
    {
        if (empty($from)) {
            throw new \Exception('From must be specified.');
        }
        if (empty($to)) {
            throw new \Exception('To must be specified.');
        }
        if (empty($subject)) {
            throw new \Exception('Subject must be specified.');
        }
        if (empty($message)) {
            throw new \Exception('Message must be specified.');
        }
        $mailTo = [];
        if (is_string($to)) {
            $mailTo[] = $to;
        } else {
            foreach ($to as $email) {
                $mailTo[] = $email;
            }
        }
        $host = trim(shell_exec('hostname'));
        $email = (new Email())
            ->from($from)
            ->subject("$host - COURSE CATALOG: $subject")
            ->text($message);
        foreach ($mailTo as $address) {
            $email->addTo($address);
        }
        $this->mailer->send($email);
    }
}
