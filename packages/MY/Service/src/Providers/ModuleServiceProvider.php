<?php


namespace MY\Service\Providers;


use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{

    protected $models = [
        \MY\Service\Models\Service::class,
        \MY\Service\Models\ServiceTranslation::class,
        \MY\Service\Models\ServiceImage::class,
    ];
}