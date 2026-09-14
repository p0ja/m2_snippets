<?php

declare(strict_types=1);

namespace M2\CliEmail\Console;

use M2\CliEmail\Service\SendEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Changed:
 * - the constructor calls parent::__construct(); without it Symfony never initialised the command (fatal error);
 * - InputOption was used without an import;
 * - the dependency is named after the service it is, not "helper";
 * - the command reports whether the email was sent and returns FAILURE when it was not.
 */
class SendEmailCommand extends Command
{
    private const NAME = 'name';

    /**
     * Constructor
     *
     * @param SendEmail $sendEmail
     * @param string|null $name
     */
    public function __construct(
        private readonly SendEmail $sendEmail,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:send-email');
        $this->setDescription('Sends the custom email template to the configured receivers');
        $this->addOption(self::NAME, null, InputOption::VALUE_OPTIONAL, 'Name used in the greeting');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getOption(self::NAME);

        if (!$this->sendEmail->sendMail($name === null ? null : (string)$name)) {
            $output->writeln(
                '<error>The email was not sent: the service is disabled or sending failed (see the log).</error>'
            );

            return Command::FAILURE;
        }

        $output->writeln('<info>The email has been sent.</info>');

        return Command::SUCCESS;
    }
}
