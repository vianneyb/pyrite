<?php

namespace Pyrite\Templating;

use Pyrite\Exception\TemplateNotFoundException;
use Pyrite\Response\ResponseBag;

class Renderer
{
    private $rootDir;

    private $supportedExtensions;

    public function __construct($rootDir)
    {
        $this->rootDir             = $rootDir;
        $this->supportedExtensions = array();
    }

    /**
     * Register a template engine.
     *
     * @param Engine $engine        the template engine
     * @param string $extensionsStr list of supported extension, as a
     *                              string. Extensions are separated by
     *                              commas.
     */
    public function registerEngine(Engine $engine, $extensionsStr)
    {
        $extensions = explode(',', $extensionsStr);

        foreach ($extensions as $extension) {
            $this->supportedExtensions[$extension] = $engine;
        }
    }

    /**
     * Render a template
     *
     * @param string $template template path
     * @param array  $data     data passed to the view
     */
    public function render($template, array $data)
    {
        if (is_a($data, 'Pyrite\Response\ResponseBag')) {
            $data = $data->getAll();
        }

        $this->checkTemplatePath($template);

        $extension = $this->getTemplateExtension($template);

        if (!array_key_exists($extension, $this->supportedExtensions)) {
            throw new TemplateNotFoundException(sprintf("File format not supported: %s", $extension));
        }

        $engine = $this->supportedExtensions[$extension];

        return $engine->render($template, $data);
    }

    private function checkTemplatePath($template)
    {
        if (!file_exists($this->rootDir . $template)) {
            throw new TemplateNotFoundException(sprintf("Template not found: %s", $template), 500);
        }
    }

    private function getTemplateExtension($template)
    {
        return pathinfo($template, PATHINFO_EXTENSION);
    }
}
