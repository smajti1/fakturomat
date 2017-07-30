<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class SimpleCsv
{
    public $headers = [];
    public $lines = [];
    public $file;
    protected $readHeaders = true;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setFile($file): SimpleCsv
    {
        $this->file = $file;

        return $this;
    }

    public function fileFromRequest(string $key): SimpleCsv
    {
        $this->file = $this->request->file($key);

        return $this;
    }

    public function readHeaders($readHeaders = true): SimpleCsv
    {
        $this->readHeaders = $readHeaders;

        return $this;
    }

    public function dontReadHeaders(): SimpleCsv
    {
        $this->readHeaders = false;

        return $this;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function reset(): SimpleCsv
    {
        $this->headers = [];
        $this->lines = [];
        $this->file = null;
        $this->readHeaders = true;

        return $this;
    }

    public function readData(): SimpleCsv
    {
        $file = fopen($this->file, 'r');
        if ($this->readHeaders) {
            $this->headers = array_flip(fgetcsv($file));
        }
        while (($line = fgetcsv($file)) !== false) {
            $this->lines[] = $line;
        }
        fclose($file);

        return $this;
    }

    public function new(): SimpleCsv
    {
        $new = clone $this;
        $new->reset();

        return $new;
    }

    public function copy(): SimpleCsv
    {
        return clone $this;
    }

    public function unset($key)
    {
        unset($this->lines[$key]);
    }
}