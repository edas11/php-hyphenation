<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.20
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Output;

class WebOutput implements BufferedOutput
{
    private $contentType = 'application/json';
    private $pagePath = '';
    private $outputData = [];


    public function set(string $key, $data): void
    {
        $this->outputData[$key] = $data;
    }

    public function flush(): void
    {
        if ($this->contentType === 'application/json') {
            echo json_encode($this->outputData);
        } else {
            $this->includeHtmlPage();
        }
    }

    private function includeHtmlPage(): void
    {
        $data = $this->outputData;
        require $this->pagePath;
    }

    public function configureOutput(string $contentType, string $pagePath = '')
    {
        header("content-type: $contentType");
        $this->contentType = $contentType;
        $this->pagePath = $pagePath;
    }
}