<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Service;

use App\Core\Warning\Application\Model\GeneratorOutputCollection;

class WarningsGenerator
{
    private array $generators = [];

    public function __construct(
        array $generators = []
    ) {
        $this->setGenerators($generators);
    }

    public function generate(): GeneratorOutputCollection
    {
        $output = [];

        /**
         * @var  WarningsGeneratorInterface $generator
         */
        foreach ($this->generators as $generator) {
            $output[] = $generator->generate();
        }

        return new GeneratorOutputCollection($output);
    }

    private function setGenerators(array $generators): void
    {
        /** @var WarningsGeneratorInterface $generator */
        foreach ($generators as $generator) {
            if (!$generator instanceof WarningsGeneratorInterface) {
                throw new \RuntimeException('Invalid warnings generator');
            }

            $this->generators = $generators;
        }
    }
}
