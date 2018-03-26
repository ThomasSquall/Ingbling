<?php

/**
 * Class HomeController.
 * [\PHPEasyAPI\Server]
 */
class HomeController extends ControllerBase
{
    /**
     * HomeController constructor.
     */
    public function __construct() { }

    /**
     * [\PHPEasyAPI\Enrichment\Endpoint(method = "GET", url = "")]
     */
    public function getIndex() { $this->render(); }
}