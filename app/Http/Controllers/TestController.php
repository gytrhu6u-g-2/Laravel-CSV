<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CSV\Common;

class TestController extends Controller
{
    //

    public function practice()
    {
        $print1 = 5;
        $print2 = 11;

        $column = 2;
        $chosu = 10;

        $soTaba = 2;


        $array = array();

        for ($i = $print1; $i <= $print2; $i++) {
            array_push($array, $i);
        }

        $common = new Common();
        $common->Nchunk($array, $column, $chosu, $print1, $print2);
    }
}
