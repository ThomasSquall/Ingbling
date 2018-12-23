<?php

namespace Ingbling\MVC;

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
                throw new \Exception("Template $name does not exist for controller $controller");
        }

        include $template;
    }

    protected function redirect($uri = "")
    {
        if (!starts_with($uri, "http"))
            $uri = BASE_URL . $uri;

        header("Location: $uri");
        exit();
    }

    protected function requireHeaderScript($script, $baseDir = "/assets/js/")
    {
        $this->cleanJS($script);
        $this->cleanDir($baseDir);

        $this->requires["HeaderScripts"][] = BASE_DIR . $baseDir . $script;
    }

    protected function requireFooterScript($script, $baseDir = "/assets/js/")
    {
        $this->cleanJS($script);
        $this->cleanDir($baseDir);

        $this->requires["FooterScripts"][] = BASE_DIR . $baseDir . $script;
    }

    protected function requireStyle($style, $baseDir = "/assets/css/")
    {
        $this->cleanCSS($style);
        $this->cleanDir($baseDir);

        $this->requires["Styles"][] = BASE_DIR . $baseDir . $style;
    }

    public function getHeaderScripts() { return $this->requires["HeaderScripts"]; }
    public function getFooterScripts() { return $this->requires["FooterScripts"]; }
    public function getStyles() { return $this->requires["Styles"]; }

    private function cleanJS(&$script)
    {
        if (!ends_with($script, ".js"))
            $script .= ".js";

        $this->cleanDir($baseDir);
    }

    private function cleanCSS(&$style)
    {
        if (!ends_with($style, ".css"))
            $style .= ".css";

        $this->cleanDir($baseDir);
    }

    private function cleanDir(&$dir)
    {
        if (!ends_with($dir, "/"))
            $dir .= "/";

        if (!starts_with($dir, "/"))
            $dir = "/" . $dir;
    }
}