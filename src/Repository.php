<?php

namespace October\Rain\Config;

use Exception;
use Illuminate\Config\Repository as RepositoryBase;
use October\Rain\Config\DataWriter\FileWriter;

class Repository extends RepositoryBase
{
    protected FileWriter $writer;

    public function __construct(FileWriter $writer, array $items = [])
    {
        parent::__construct($items);
        $this->writer = $writer;
    }

    public function write(string $key, $value): bool
    {
        list($filename, $item) = $this->parseKey($key);

        $result = $this->writer->write($item, $value, $filename);

        throw_if(! $result, new Exception('File could not be written to'));

        $this->set($key, $value);

        return $result;
    }

    private function parseKey(string $key): array
    {
        return preg_split('/\./', $key, 2);
    }
}
