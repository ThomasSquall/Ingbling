<?php

class ControllerBase
{
    private $templates = [];
    private $requires = [
        'HeaderScripts' => [],
        'FooterScripts' => [],
        'Styles' => []
    ];

    public function render()
    {
        require_once APP_DIR . "templates/Master.php";
    }

    protected function addTemplate($name)
    {
        $path = APP_DIR . "templates/" . explode("Controller", get_class($this))[0] . "/";
        $this->templates[] = strtolower($path) . $name . ".php";
    }

    protected function requireHeaderScript($url) { $this->requires['HeaderScripts'][] = $url; }
    protected function requireFooterScript($url) { $this->requires['FooterScripts'][] = $url; }
    protected function requireStyle($url) { $this->requires['Styles'][] = $url; }

    public function getTemplates() { return $this->templates; }
    public function getHeaderScripts() { return $this->requires['HeaderScripts']; }
    public function getFooterScripts() { return $this->requires['FooterScripts']; }
    public function getStyles() { return $this->requires['Styles']; }
}