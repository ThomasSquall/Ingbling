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

    private $components = [];

    public final function __construct()
    {
        $this->Title = "";

        foreach (glob(APP_DIR . "components/*.html") as $file)
            $this->components[] = $file;

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

        ob_start();
        include $template;
        $content = ob_get_clean();

        $this->replaceComponents($content);

        echo $content;
    }

    private function replaceComponents(&$content)
    {
        if (string_contains($content, "{{component: ") && string_contains($content, "}}"))
        {
            $components = strings_between($content, "{{component: ", "}}");

            foreach ($components as $component)
            {
                $key = "{{component: " . trim($component) . "}}";
                $name = $component;
                $args = [];

                if (string_contains($name, ","))
                {
                    $args = explode(",", $name);
                    $name = $args[0];
                    unset($args[0]);
                }

                $component = APP_DIR . "components/$name.html";

                if (in_array($component, $this->components))
                {
                    $component = file_get_contents($component);

                    foreach ($args as $arg)
                    {
                        if (!string_contains($arg, ":"))
                            continue;

                        $value = explode(":", $arg);
                        $component = str_replace("{{" . trim($value[0]) . "}}", trim($value[1]), $component);
                    }

                    $content = str_replace($key, $component, $content);
                }
            }
        }
    }

    protected function redirect($uri = "")
    {
        if (!string_starts_with($uri, "http"))
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
        if (!string_ends_with($script, ".js"))
            $script .= ".js";

        $this->cleanDir($baseDir);
    }

    private function cleanCSS(&$style)
    {
        if (!string_ends_with($style, ".css"))
            $style .= ".css";

        $this->cleanDir($baseDir);
    }

    private function cleanDir(&$dir)
    {
        if (!string_ends_with($dir, "/"))
            $dir .= "/";

        if (!string_starts_with($dir, "/"))
            $dir = "/" . $dir;
    }
}