<?php

namespace App\Services;

class GfileReaderService
{
    /**
     * Returns an array of data obtained from a reference in the environment parameter.
     */
    public static function getContent(): array
    {
        $url = env('FILE_PATH');
        $resp = [];
        if ($url && str_contains($url, 'https://docs.google.com/spreadsheets/d/')) {
            $arr = explode('/', $url);
            if (count($arr) > 1) {
                $parse = parse_url($url);
                $arr[count($arr) - 1] = 'export?format=csv#' . $parse['query'];
                $url = implode('/', $arr);
                $csv = file_get_contents($url);
                $csv = explode("\r\n", $csv);
                $resp = array_map('str_getcsv', $csv);
            }
        }
        return $resp;
    }

    /**
     * Returns a two-dimensional array,
     * where the keys of each nested array are the values ​​of the first nested array from the passed one.
     */
    public static function processCsv(array $data): array
    {
        $resp = [];
        if ($data) {
            $first = array_shift($data);
            foreach ($data as $user) {
                $resp[] = array_combine($first, $user);
            }
        }
        return $resp;
    }
}
