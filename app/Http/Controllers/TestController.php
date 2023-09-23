<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CSV\Common;

class TestController extends Controller
{
    //

    public function practice()
    {
        $print1 = 1;
        $print2 = 4000;

        $retsu = 2;
        $chosu = 10;

        $soTaba = 2;


        $array = array();

        for ($i = $print1; $i <= $print2; $i++) {
            array_push($array, $i);
        }

        $common = new Common();
        $common->Nchunk($array, $retsu, $chosu, $soTaba, $print2);

    }
}
