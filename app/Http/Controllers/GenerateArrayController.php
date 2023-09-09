<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateArrayController extends Controller
{
    // CSV用の配列を作る
    public function generateArr(Request $request)
    {
        $input = $request->all();
        $arr = [];

        // シートの場合
        if ($input['completion'] == 1) {

            // 印字内容の範囲分配列に追加
            for ($i = (int)$input['print_information1']; $i < (int)$input['print_information2'] + 1; $i++) {
                array_push($arr, $i);
            }
            // インデックスを仕上げ列数に分ける
            $chunk = array_chunk($arr, (int)$input['column']);

            // sessionに変数を保存
            $request->session()->put('csv_chunk', array_chunk($arr, (int)$input['column']));

            return response()->json($chunk);
        }
    }
}
