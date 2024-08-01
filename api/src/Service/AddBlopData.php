<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\GraphQl\State\Processor\NormalizeProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class AddBlopData implements ProcessorInterface
{
    public function __construct(
        private NormalizeProcessor $decorated,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?array
    {
        $data = $this->decorated->process($data, $operation, $uriVariables, $context);
        $data['blop'] = [
            ['field' => 'MySuperTest !', 'count' => 65],
            ['field' => 'MySuperTest !', 'count' => 65],
            ['field' => 'MySuperTest !', 'count' => 65],
        ];

        return $data;
    }
}
