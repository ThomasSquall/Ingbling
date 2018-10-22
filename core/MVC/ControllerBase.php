<?php

abstract class ControllerBase
{
    protected $Title;

    private $requires = [
        'HeaderScripts' => [],
        'FooterScripts' => [],
        'Styles' => []
    ];

    public final function __construct()
    {
        $this->Title = "";
        $this->preInit();
        $this->init();
        $this->postInit();
    }

    public function preInit() {}
    public function init() {}
    public function postInit() {}

    public function render()
    {
        require_once APP_DIR . "templates/Master.php";
    }

    protected function addTemplate($name)
    {
        $controller = get_class($this);
        $path = APP_DIR . "templates/" . explode("Controller", $controller)[0] . "/";
        $template = strtolower($path) . $name . ".php";

        if (!file_exists($template))
        {
            $path = APP_DIR . "templates/default/";
            $template = strtolower($path) . $name . ".php";

            if (!file_exists($template))
                throw new Exception("Template $name does not exist for controller $controller");
        }

        include $template;
    }

    protected function requireHeaderScript($url) { $this->requires['HeaderScripts'][] = "app/assets/js/" . $url; }
    protected function requireFooterScript($url) { $this->requires['FooterScripts'][] = "app/assets/js/" . $url; }
    protected function requireStyle($url) { $this->requires['Styles'][] = "app/assets/css/" . $url; }

    public function getHeaderScripts() { return $this->requires['HeaderScripts']; }
    public function getFooterScripts() { return $this->requires['FooterScripts']; }
    public function getStyles() { return $this->requires['Styles']; }
}