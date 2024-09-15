<?php

namespace App\Services;

class GfileReaderService
{
    public function __construct(
        readonly string $url = 'https://docs.google.com/spreadsheets/d/1mU-SdhP12-zJntAcH8HR6bLEMuWNscuHzQYGrMYFXtQ/export?format=csv#gid=0'
    ) {}

    public function getContent(): array
    {
        $csv = file_get_contents($this->url);
        $csv = explode("\r\n", $csv);
        return array_map('str_getcsv', $csv);
    }
}
