<?php

namespace Ingbling\MVC;

class Resolver
{
    /**
     * @var \PHPEasyAPI\Resolver $resolver
     */
    private $resolver;

    public function __construct()
    {
        $this->resolver = new \PHPEasyAPI\Resolver();

        $this->resolver->setBaseUrl(BASE_URL);

        foreach (glob(APP_DIR . "controllers/*Controller.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $controller = explode(".php", $files[count($files) - 1])[0];
            $this->resolver->bindListener(new $controller());
        }
    }

    public function resolve($callback = null) { $this->resolver->resolve($callback); }
}