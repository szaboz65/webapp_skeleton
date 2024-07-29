<?php

namespace App\Support;

/**
 * Minimal template engine.
 */
class TemplateEngine
{
    private string $fileExt;

    private string $templatePath;

    private array $valueMap;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->fileExt = 'html';
        $this->templatePath = '';
        $this->valueMap = [];
    }

    /**
     * Set file extension.
     *
     * @param string $fileExt The fileext
     *
     * @return void
     */
    public function setFileExt(string $fileExt): void
    {
        $this->fileExt = $fileExt;
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExt(): string
    {
        return $this->fileExt;
    }

    /**
     * Set the template path.
     *
     * @param string $templatePath The path
     *
     * @return void
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * Get the value map.
     *
     * @return array
     */
    public function getValueMap(): array
    {
        return $this->valueMap;
    }

    /**
     * Set the value map.
     *
     * @param array $nameValueMap The map
     *
     * @return void
     */
    public function setValueMap(array $nameValueMap): void
    {
        $this->valueMap = $nameValueMap;
    }

    /**
     * Add value to the map.
     *
     * @param string $name The key
     * @param mixed $value The value
     *
     * @return void
     */
    public function addValue(string $name, $value): void
    {
        $this->valueMap[$name] = $value;
    }

    /**
     * Get filename with path.
     *
     * @param string $templatename The filename
     *
     * @return string
     */
    public function getFileName(string $templatename): string
    {
        return $this->templatePath . DIRECTORY_SEPARATOR . $templatename . '.' . $this->fileExt;
    }

    /**
     * Gets template contents from specific file.
     *
     * @param string $templatename The filename
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getFileContents(string $templatename): string
    {
        $filename = $this->getFileName($templatename);
        if (!file_exists($filename)) {
            throw new \Exception("The template file at '$filename' does not exist.");
        }

        $content = file_get_contents($filename);

        return $content == false ? '' : $content;
    }

    /**
     * Render template with values.
     *
     * @param string $templatename The template
     * @param array $nameValueMap The values
     *
     * @return string
     */
    public function renderValueMap(string $templatename, array $nameValueMap): string
    {
        $this->setValueMap($nameValueMap);

        return $this->render($templatename);
    }

    /**
     * Render a template.
     *
     * @param string $templatename The filename
     *
     * @return string
     */
    public function render(string $templatename): string
    {
        $template = $this->getFileContents($templatename);

        return $this->renderTemplate($template);
    }

    /**
     * Render a template.
     *
     * @param string $template The template
     *
     * @return string
     */
    public function renderTemplate(string $template): string
    {
        $parseTemplate = function ($result) {
            return $this->parseTemplateStatement(trim($result[1]));
        };

        return preg_replace_callback('/{(.*?)}/', $parseTemplate, $template) ?? '';
    }

    /**
     * Parse statement.
     * Redirects template statements (if, foreach or include) to appropriate parsing functions.
     *
     * @param string $statement The statement
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function parseTemplateStatement(string $statement): string
    {
        $prefix = $this->getFuncPrefix($statement);
        switch ($prefix) {
            case 'var':
                return $this->parseVar($statement);
            case 'if':
                return $this->parseIf($statement);
            case 'foreach':
                return $this->parseForeach($statement);
            case 'include':
                return $this->parseInclude($statement);
            default:
                throw new \Exception("Templating engine doesn't recognize the prefix '$prefix'");
        }
    }

    /**
     * Parse Variable.
     *
     * @param string $statement The sztatement
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function parseVar(string $statement): string
    {
        if (!isset($this->valueMap[$statement])) {
            throw new \Exception("The value for the placeholder '$statement' does not exist.");
        }

        return $this->valueMap[$statement];
    }

    /**
     * Parse IF.
     *
     * @param string $statement The statement
     *
     * @return string
     */
    protected function parseIf(string $statement): string
    {
        $params = $this->getParams($statement);
        $parts = explode(',', $params);
        $baseparam = trim($parts[0]);
        if (isset($this->valueMap[$baseparam])) {
            return $this->valueMap[$baseparam];
        }
        if (count($parts) > 1) {
            $defparam = trim($parts[1]);
            if (isset($this->valueMap[$defparam])) {
                return $this->valueMap[$defparam];
            }
        }

        return '';
    }

    /**
     * Parse Foreach.
     *
     * @param string $statement The statement
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function parseForeach(string $statement): string
    {
        $params = $this->getParams($statement);
        $parts = explode(',', $params);
        $baseparam = trim($parts[0]);

        if (!isset($this->valueMap[$baseparam]) || !is_array($this->valueMap[$baseparam])) {
            throw new \Exception("Templating Engine's 'foreach' declaration requires an array: $baseparam");
        }

        $html = '';
        $format = count($parts) > 1 ? $parts[1] : '%s';
        foreach ($this->valueMap[$baseparam] as $string) {
            if (!is_string($string)) {
                throw new \Exception("Templating Engine's 'foreach' declaration "
                    . "requires an array composed of strings: $baseparam:$string");
            }
            $html .= sprintf($format, $string);
        }

        return $html;
    }

    /**
     * Parse Include.
     *
     * @param string $statement The statement
     *
     * @return string
     */
    protected function parseInclude(string $statement): string
    {
        $params = $this->getParams($statement);

        return $this->render($params);
    }

    /**
     * Returns whatever's {in here} <h1>{or here}</h1>.
     *
     * @param string $string A string
     *
     * @return string
     */
    protected function getFuncPrefix(string $string): string
    {
        $regexString = "/^(.*?)\((.*)\)/";
        $matches = [];
        $ret = preg_match($regexString, $string, $matches);

        return $ret ? trim($matches[1]) : 'var';
    }

    /**
     * Returns whatever's if(in here) include(or here) foreach(or maybe he).
     *
     * @param string $string A string
     *
     * @return string
     */
    protected function getParams(string $string): string
    {
        $regexString = "/\((.*)\)/";
        $matches = [];
        $ret = preg_match($regexString, $string, $matches);

        return $ret ? trim($matches[1]) : '';
    }
}
