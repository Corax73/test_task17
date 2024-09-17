<?php

namespace App\Http\Controllers\Api\v1;

use App\Services\GfileReaderService;
use App\Http\Controllers\Controller;
use App\Services\DateService;
use Illuminate\Http\Request;

class GfileController extends Controller
{
    public function get(): \Illuminate\Http\Response
    {
        $data = GfileReaderService::getContent();
        $data = GfileReaderService::processCsv($data);
        $data = DateService::searchByDate($data, time(), 'Дата рождения', 'Дата общения', 'd.m.Y');
        return response($data);
    }
}
