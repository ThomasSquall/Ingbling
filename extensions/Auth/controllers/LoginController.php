<?php

/**
 * Class LoginController.
 * @PHPEasyAPI\Server("login")
 */
class LoginController extends Ingbling\MVC\ControllerBase
{
    /**
     * HomeController constructor.
     */
    public function init() { $this->Title = "Login"; }

    /**
     * @PHPEasyAPI\Enrichment\Endpoint(method = "GET", url = "")
     */
    public function getIndex() { $this->render(); }

    /**
     * @PHPEasyAPI\Enrichment\Endpoint(method = "POST", url = "")
     */
    public function postIndex()
    {
        global $database;

        $user = new User();

        $database->findOne
        (
            $user,
            new MongoDriver\Filter
            (
                "username",
                $_POST["username"],
                MongoDriver\Filter::IS_EQUAL
            )
        );

        if ($user !== false && User::verifyPassword($_POST["password"], $user->password))
        {
            $_SESSION["username"] = $user->username;
            $this->redirect();
        }

        $this->redirect("login");
    }
}