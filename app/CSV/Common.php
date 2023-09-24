<?php

namespace App\CSV;

class Common
{
    public function Nchunk($array, $column, $chosu, $print1, $print2)
    {
        $index = 0;
        $slice_index = 0;
        $tmp_array = array();


        $all = ceil(((($print2 - $print1) + 1)) / ($column * $chosu));

        $last = $column * $chosu;

        // 丁数✖️列分回す
        for ($j = 0; $j < $all; $j++) {
            // インデックスの開始から終わりをスライス
            $output = array_slice($array, $slice_index, $last);

            // 丁数
            for ($k = 0; $k < $chosu; $k++) {
                // 列
                for ($l = 1; $l <= $column; $l++) {
                    // キーが存在するかの判断
                    if (isset($output[$index])) {
                        array_push($tmp_array, $output[$index]);
                    } else {
                        array_push($tmp_array, "");
                    }
                    // 列数と等しい場合break
                    if ($l == $column) {
                        break;
                    }
                    $index += $chosu;
                }
                $index = $index - ($chosu * ($column - 1));
                $index += 1;
            }
            // 0に戻す
            $index = 0;
            $slice_index += $last;
        }

        // 列数に分ける
        $array = array_chunk($tmp_array, $column);
    }
}
