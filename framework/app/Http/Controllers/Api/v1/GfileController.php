<?php

namespace App\Http\Controllers\Api\v1;

use App\Services\GfileReaderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GfileController extends Controller
{
    public function get()
    {
        $reader = new GfileReaderService();
        return response($reader->getContent());
    }
}
