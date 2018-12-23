<?php

/**
 * Class RegisterController.
 * [\PHPEasyAPI\Server("register")]
 */
class RegisterController extends Ingbling\MVC\ControllerBase
{
    /**
     * HomeController constructor.
     */
    public function init() { $this->Title = "Register"; }

    /**
     * [\PHPEasyAPI\Enrichment\Endpoint(method = "GET", url = "")]
     */
    public function getIndex() { $this->render(); }

    /**
     * [\PHPEasyAPI\Enrichment\Endpoint(method = "POST", url = "")]
     */
    public function postIndex()
    {
        global $database;

        $exist = new User();
        $exist->username = $_POST["username"];

        $database->findOne
        (
            $exist,
            new MongoDriver\Filter
            (
                "username",
                $_POST["username"],
                MongoDriver\Filter::IS_EQUAL
            )
        );

        if ($exist !== false)
            $this->redirect("register");

        $user = new User();
        $user->username = $_POST["username"];
        $user->password = User::hashPassword($_POST["password"]);
        $database->insert($user);

        $this->redirect("login");
    }
}