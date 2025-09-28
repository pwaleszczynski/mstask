<?php

declare(strict_types=1);

namespace App\Core\Warning\UI;

use App\Core\Warning\Application\Model\GeneratorOutput;
use App\Core\Warning\Application\Service\WarningsGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:warnings:generate',
    description: 'Generates warnings.'
)]
class GenerateWarningsCommand extends Command
{
    public function __construct(
        private readonly WarningsGenerator $generator,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->generator->generate()->getItems();

        $output->writeln('-------------------------------------');
        /** @var GeneratorOutput $result */
        foreach ($results as $result) {
            $output->writeln('Warning type: <fg=green>' . $result->type->value . '</>');
            $output->writeln('-------------------------------------');
            $output->writeln('new: <fg=green>' . $result->new . '</>');
            $output->writeln('sustained: <fg=green>' . $result->sustained . '</>');
            $output->writeln('closed: <fg=green>' . $result->closed . '</>');
            $output->writeln('-------------------------------------');
        }
        return Command::SUCCESS;
    }
}
