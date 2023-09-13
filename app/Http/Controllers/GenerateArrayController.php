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

        if ($input['direction'] == 1) {
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
                        $chunk = $this->Zsort($arr, $input['column'], $input['sort']);
                    }
                }

                // 流れ方向　逆N字
                if ($input['floating'] == 2) {
                    // 初期の値を設定
                    $arr = [0];

                    // 最後のキーの0番目の値
                    $last = $this->get_last_key_value($arr);

                    // 列数
                    $column = (int)$input['column'];
                    // 丁数
                    $piece = $input['completion_piece'] / $column;
                    // 印字内容1
                    $print_information1 = (int)$input['print_information1'];
                    // 印字内容2
                    $print_information2 = (int)$input['print_information2'];
                    // 仕上げ付丁数
                    $completion_piece = (int)$input['completion_piece'];

                    // 昇順・降順
                    if ($input['sort']) {
                        $chunk = $this->Nsort($arr, $last, $piece, $print_information1, $print_information2, $completion_piece, $column, $input['sort']);
                    }
                }
            }
        }
        // sessionに変数を保存
        $request->session()->put('csv_chunk', $chunk);
        return response()->json($chunk);
    }

    /**
     * Z字の昇順・降順の並び替え
     * @param arr
     * @param column
     * @return arr
     */
    public function Zsort($arr, $column, $sort)
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

        // 降順
        if ($sort == 2) {
            return $chunk = $this->descendDirection($chunk);
        }

        return $chunk;
    }

    /**
     * 逆N字の昇順・降順の並び替え
     * @param arr
     * @param column
     * @return arr
     */
    public function Nsort($arr, $last, $piece, $print_information1, $print_information2, $completion_piece, $column, $sort)
    {
        // 初めは0番目を置き換えるため1に設定
        $i = 1;
        // 0番目の配列を印字内容1に置き換えるため
        $first_arr = 0;

        // 最後のキーの0番目の値＋丁数が印字内容2の値を上回るまでループ
        while ($last + $piece <= $print_information2) {

            // 丁数の分だけfor文を回す
            for ($i; $i < $piece; $i++) {
                // 0番目を印字内容1に置き換える
                if ($first_arr == 0) {
                    $arr[0] = $print_information1;
                    $print_information1 += 1;
                }
                if ($print_information1 >= $print_information2 + 1) {
                    break;
                }
                // 配列に印字内容1を入れていく
                array_push($arr, $print_information1);
                $print_information1 += 1;

                // 初回1ループ目の配列0番目だけ
                $first_arr += 1;
            }

            // 最後のキーの0番目の値
            $last = $this->get_last_key_value($arr);

            // 逆N字　計算
            $print_information1 = $piece * ($column - 1) + 1;
            $print_information1 += $last;

            // 0に戻す
            $i = 0;

            // 最後のキーの0番目の値＋丁数が印字内容2の値を上回ると強制でbreak
            if ($print_information1 >= $print_information2 + 1) {
                break;
            }
        }

        // 配列の数
        $count = count($arr);
        // 配列を一列で設定
        $chunk = array_chunk($arr, 1);
        // 丁数の固定値
        $fixed_piece = $completion_piece / $column;
        for ($i = 0; $i < $count; $i++) {
            // 配列の値
            $arr_value = $arr[$i];
            for ($j = 1; $j < $column; $j++) {
                // 配列の値＋丁数
                $arr_piece = $arr_value + $piece;

                // 印字内容2以上になればbreak
                if ($arr_piece >= $print_information2 + 1) {
                    break;
                }
                array_push($chunk[$i], $arr_piece);
                // 固定値を丁数に代入
                $piece += $fixed_piece;
            }
            // 丁数を元に戻す
            $piece = $piece / $j;
            $j = 1;
        }
        // 0番目の値数
        $first = count($chunk[0]);
        // 配列の数
        $count = count($chunk);
        for ($i = 0; $i < $count; $i++) {
            // キーの値数
            $last = count($chunk[$i]);

            // 0番目と最後のキーの値が等しくない場合、足りない分「""」を追加
            if ($first != $last) {
                $shortage = $first - $last;
                for ($k = 0; $k < $shortage; $k++) {
                    array_push($chunk[$i], "");
                }
                $k = 0;
            }
        }

        // 降順
        if ($sort == 2) {
            return $chunk = $this->descendDirection($chunk);
        }

        return $chunk;
    }

    /**
     * 最後のキーの0番目の値を取得
     * @param arr
     * @return int 
     */
    public function get_last_key_value($arr)
    {
        // 最後のキー取得
        $last_key = array_keys($arr)[count($arr) - 1];
        // 最後のキーの0番目の値
        $last = $arr[$last_key];

        return $last;
    }

    /**
     * 天地　降順
     * @param chunk
     * @return arr
     */
    public function descendDirection($chunk)
    {
        krsort($chunk);

        for ($i = 0; $i < count($chunk); $i++) {
            $chunk[$i] = array_reverse($chunk[$i]);
        }
        return $chunk;
    }
}
