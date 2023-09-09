<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateArrayController extends Controller
{
    public function generateArr(Request $request)
    {
        $input = $request->all();

        // シートの場合
        if ($input['completion'] == 1) {
            $arr = [];
            // 印字内容の範囲分$arrに追加
            for ($i = (int)$input['print_information1']; $i < (int)$input['print_information2'] + 1; $i++) {
                array_push($arr, $i);
            }
            // インデックスを仕上げ列数に分ける
            $chunk = array_chunk($arr, (int)$input['column']);
            // dd($chunk);

            // $conversion = implode(",", $chunk);
            // dd($conversion);
            // return response()->json($chunk);
            return $chunk;
        }
    }
}
