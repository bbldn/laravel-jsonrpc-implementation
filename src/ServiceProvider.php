<?php

namespace BBLDN\JSONRPC;

use Illuminate\Support\ServiceProvider as Base;
use BBLDN\JSONRPC\Application\Hydrator\Hydrator;
use BBLDN\JSONRPC\Application\Kernel as JSONRPCKernel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use BBLDN\JSONRPC\Application\ResolverRegistry\ResolverRegistry;
use BBLDN\JSONRPC\Infrastructure\Symfony\Controller\JSONRPCController;

class ServiceProvider extends Base
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Hydrator::class);
        $this->app->singleton(JSONRPCKernel::class);
        $this->app->singleton(JSONRPCController::class);
        $this->app->singleton(ResolverRegistry::class, function (): ResolverRegistry {
            /** @var ConfigRepository $config */
            $config = $this->app['config'];

            return new ResolverRegistry($config->get('jsonrpc', []));
        });
    }
}