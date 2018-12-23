<?php

/**
 * Class HomeController.
 * [\PHPEasyAPI\Server]
 */
class HomeController extends Ingbling\MVC\ControllerBase
{
    /**
     * HomeController constructor.
     */
    public function init() { $this->Title = "Home"; }

    /**
     * [\PHPEasyAPI\Enrichment\Endpoint(method = "GET", url = "")]
     */
    public function getIndex() { $this->render(); }
}