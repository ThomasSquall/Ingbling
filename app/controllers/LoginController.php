<?php

/**
 * Class LoginController.
 * [\PHPEasyAPI\Server("login")]
 */
class LoginController extends ControllerBase
{
    /**
     * HomeController constructor.
     */
    public function init() { $this->Title = "Login"; }

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

        $user = new User();

        $database->findOne
        (
            $user,
            new MongoDriver\Filter
            (
                'username',
                $_POST['username'],
                MongoDriver\Filter::IS_EQUAL
            )
        );

        if (User::verifyPassword($_POST['password'], $user->password))
        {
            $_SESSION['username'] = $user->username;
            header('Location: ' . BASE_URL);
        }

        header('Location: ' . BASE_URL . 'login');
    }
}