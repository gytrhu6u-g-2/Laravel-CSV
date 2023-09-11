<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateArrayController extends Controller
{
    /**
     * CSV用の配列を作る
     * @param request
     * @return json
     */
    public function generateArr(Request $request)
    {
        $input = $request->all();
        $arr = [];

        // シートの場合
        if ($input['completion'] == 1) {

            // 流れ方向　Z字
            if ($input['floating'] == 1) {
                // 印字内容の範囲分配列に追加
                for ($i = (int)$input['print_information1']; $i < (int)$input['print_information2'] + 1; $i++) {
                    array_push($arr, $i);
                }

                // 昇順・降順
                if ($input['sort']) {
                    $chunk = $this->sort($arr, $input['column'], $input['sort']);
                }
            }
        }

        // sessionに変数を保存
        $request->session()->put('csv_chunk', $chunk);
        return response()->json($chunk);
    }

    /**
     * 昇順・降順の並び替え
     * @param arr
     * @param column
     * @return arr
     */
    public function sort($arr, $column, $sort)
    {
        // インデックスを仕上げ列数に分ける
        $chunk = array_chunk($arr, (int)$column);
        // 0番目の値数
        $first = count($chunk[0]);
        // 最後のキー取得
        $last_key = array_keys($chunk)[count($chunk) - 1];
        // 最後のキーの値数
        $last = count($chunk[$last_key]);

        // 0番目と最後のキーの値が等しくない場合、足りない分「""」を追加
        if ($first != $last) {
            $shortage = $first - $last;
            for ($i = 0; $i < $shortage; $i++) {
                array_push($chunk[$last_key], "");
            }
        }

        if ($sort == 2) {
            krsort($chunk);

            for ($i = 0; $i < count($chunk); $i++) {
                $chunk[$i] = array_reverse($chunk[$i]);
            }
        }

        return $chunk;
    }
}
