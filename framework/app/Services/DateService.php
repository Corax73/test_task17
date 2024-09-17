<?php

namespace App\Services;

class DateService
{
    /**
     * Returns an array with elements selected from the passed array whose dates under the passed keys match the conditions.
     */
    public static function searchByDate(array $data, int $startingPoint, string $keyFirstDate, string $secondDate, string $format = 'd.m.Y'): array
    {
        $resp = [];
        if ($data && $startingPoint && self::checkDate($startingPoint, $format) && $keyFirstDate && $secondDate) {
            $resp = collect($data)->filter(function ($item) use ($format, $startingPoint, $keyFirstDate, $secondDate) {
                if (isset($item[$keyFirstDate]) && isset($item[$secondDate])) {
                    $item[$keyFirstDate] = self::dateCasting($item[$keyFirstDate]);
                    $item[$secondDate] = self::dateCasting($item[$secondDate]);
                    if (self::checkDate($item[$keyFirstDate], $format) && self::checkDate($item[$secondDate], $format)) {
                        if (self::strictEquality($startingPoint, $item[$keyFirstDate]) || self::gtOrEqual($startingPoint, $item[$secondDate])) {
                            return $item;
                        }
                    }
                }
            })->filter()->toArray();
        }
        return $resp;
    }

    public static function dateCasting(string $date): string
    {
        if ($date) {
            $arr = explode('.', $date);
            if (count($arr) > 1 && intval($arr[count($arr) - 1]) < 2000) {
                $arr[count($arr) - 1] = '20' . $arr[count($arr) - 1];
            }
            $date = implode('.', $arr);
        }
        return $date;
    }

    public static function checkDate(string $date, string $format = 'd.m.Y'): bool
    {
        $resp = false;
        if ($date) {
            $timestamp = strtotime($date);
            if (\DateTime::createFromFormat($format, date($format, $timestamp))) {
                $resp = true;
            }
        }
        return $resp;
    }

    public static function strictEquality(int $startingPoint, string $itemDate): bool
    {
        $resp = false;
        if ($itemDate) {
            if ($startingPoint == strtotime($itemDate) || $startingPoint - 14 * 24 * 60 * 60 == strtotime($itemDate)) {
                $resp = true;
            }
        }
        return $resp;
    }

    public static function gtOrEqual(int $startingPoint, string $itemDate, string $format = 'd.m.Y'): bool
    {
        $resp = false;
        if ($itemDate) {
            if ($startingPoint - 14 * 24 * 60 * 60 >= strtotime($itemDate)) {
                $resp = true;
            }
        }
        return $resp;
    }
}
