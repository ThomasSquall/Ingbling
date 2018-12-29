<?php

/**
 * Class LogoutController.
 * @PHPEasyAPI\Server("logout")
 */
class LogoutController extends Ingbling\MVC\ControllerBase
{
    /**
     * @PHPEasyAPI\Enrichment\Endpoint(method = "GET", url = "")
     */
    public function getIndex()
    {
        session_destroy();
        $this->redirect("login");
    }
}