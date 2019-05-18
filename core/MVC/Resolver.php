<?php

namespace Ingbling\MVC;

class Resolver
{
    /**
     * @var \PHPEasyAPI\Resolver $resolver
     */
    private $resolver;

    /**
     * @var array $middlewares
     */
    private $middlewares;

    public function __construct()
    {
        $this->resolver = new \PHPEasyAPI\Resolver();
        $this->resolver->setBaseUrl(BASE_URL);
        $this->middlewares = [];

        $this->registerControllers();
        $this->registerMiddlewares();
    }

    public function resolve()
    {
        $this->resolver->resolve
        (
            function($endpoint)
            {
                foreach ($this->middlewares as $middleware)
                    $middleware->resolve($endpoint);
            }
        );
    }

    private function registerControllers()
    {
        $this->registerExtensionsControllers();
        $this->registerUserDefinedControllers();
    }

    private function registerExtensionsControllers()
    {
        foreach (glob(EXTS_DIR . "**/controllers/*Controller.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $controller = explode(".php", $files[count($files) - 1])[0];
            $this->resolver->bindListener(new $controller());
        }
    }

    private function registerUserDefinedControllers()
    {
        foreach (glob(APP_DIR . "controllers/*Controller.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $controller = explode(".php", $files[count($files) - 1])[0];
            $this->resolver->bindListener(new $controller());
        }
    }

    private function registerMiddlewares()
    {
        $this->registerExtensionsMiddlewares();
        $this->registerUserDefinedMiddlewares();
    }

    private function registerExtensionsMiddlewares()
    {
        foreach (glob(EXTS_DIR . "**/middlewares/*Middleware.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $middleware = explode(".php", $files[count($files) - 1])[0];
            $this->middlewares[] = new $middleware();
        }
    }

    private function registerUserDefinedMiddlewares()
    {
        foreach (glob(APP_DIR . "middlewares/*Middleware.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $middleware = explode(".php", $files[count($files) - 1])[0];
            $this->middlewares[] = new $middleware();
        }
    }
}