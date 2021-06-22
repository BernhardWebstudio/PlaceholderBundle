<?php

declare(strict_types=1);

use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
};
