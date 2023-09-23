<?php

namespace App\CSV;

class Common
{
    public function Nchunk($array, $retsu, $chosu, $soTaba, $print2)
    {
        $index = 0;
        $array2 = array();


        $zenbu = ceil($print2 / ($retsu * $chosu));

        $last = $retsu * $chosu;
        // 総束数分回す
        for ($i = 0; $i < $soTaba; $i++) {
            // 丁数✖️列分回す
            for ($j = 0; $j < $zenbu; $j++) {
                $output = array_slice($array, $index, $last);

                for ($k = 0; $k < $chosu; $k++) {
                    for ($l = 0; $l < $retsu; $l++) {
                        array_push($array2, $output[$index]);
                        $index += $chosu;
                    }
                    $index += 1;
                }
            }
        }
    }
}
