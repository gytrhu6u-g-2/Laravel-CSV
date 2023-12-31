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

        // 天地
        if ($input['direction'] == 1) {
            // シート
            if ($input['completion'] == 1) {

                // レコードの総束数
                $Total_number_of_bundles_record = ceil($input['num'] / ($input['completion_data']['completion_piece'] * $input['completion_data']['a_bundle_of_sheet']));
                // １束
                $a_bundle = $input['completion_data']['completion_piece'] * $input['completion_data']['a_bundle_of_sheet'];
                // 1束のｍ (小数点以下切り上げ)
                $a_bundle_of_meters = ceil($a_bundle / $input['column'] * $input['send_designation2'] / 1000);
                // 1ファイル分の最大束数 (小数点以下切り捨て)
                $maximum_number_of_bundles = floor($input['completion_data']['max_base_sheet'] / $a_bundle_of_meters);
                // ロット数 (切り上げ)
                $rot = ceil($Total_number_of_bundles_record / $maximum_number_of_bundles);

                // 流れ方向　Z字
                if ($input['floating'] == 1) {

                    $print_information1 = (int)$input['print_information1'];
                    // レコードの総束数分for文で配列を作る数字を指定
                    for ($a = 0; $a < $Total_number_of_bundles_record; $a++) {
                        // 多次元配列
                        $subArray = [];
                        // 一束に入る数量を超えたら新しい配列を作る
                        // 印字内容の範囲分配列に追加
                        for ($i = $print_information1; $i <= (int)$input['print_information2']; $i++) {
                            // 一束の数を超えたらbreak
                            if ($i >= $print_information1 + $a_bundle) {
                                $print_information1 = $i;
                                break;
                            }
                            array_push($subArray, $i);
                        }
                        // 多次元配列に新しい配列を追加
                        array_push($arr, $subArray);
                    }

                    // 昇順・降順
                    if ($input['sort']) {
                        $chunk = $this->Zsort($arr, $input['column'], $input['sort']);
                    }
                }

                // 流れ方向　逆N字
                if ($input['floating'] == 2) {

                    // 昇順・降順
                    if ($input['sort']) {
                        $chunk = $this->Nsort($input, $Total_number_of_bundles_record, $a_bundle, $a_bundle_of_meters, $maximum_number_of_bundles);
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

        $merge = array(); // 空の配列を初期化

        // 昇順
        if ($sort == 1) {
            for ($i = 0; $i < count($arr); $i++) {
                $merge = array_merge($merge, $arr[$i]); // $chunksに新しい要素を追加
            }

            return $this->zChunk($merge, $column);
        }



        // 降順
        if ($sort == 2) {
            for ($i = 0; $i < count($arr); $i++) {
                krsort($arr[$i]);
            }

            krsort($arr);

            $first_key = array_key_first($arr);
            for ($i = $first_key; $i >= 0; $i--) {
                $merge = array_merge($merge, $arr[$i]);
            }

            return $this->zChunk($merge, $column);
        }
    }

    /**
     * Z字　列数に分けるのと、足りない分「””」追加
     * @param merge
     * @param column
     * @return chunk
     */
    public function zChunk($merge, $column)
    {
        // インデックスを仕上げ列数に分ける
        $chunk = array_chunk($merge, (int)$column);
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
        return $chunk;
    }

    /**
     * 逆N字の昇順・降順の並び替え
     * @param arr
     * @param column
     * @return arr
     */
    public function Nsort($input, $Total_number_of_bundles_record, $a_bundle, $a_bundle_of_meters, $maximum_number_of_bundles)
    {
        // 初めは0番目を置き換えるため1に設定
        $i = 1;
        // 0番目の配列を印字内容1に置き換えるため
        $first_arr = 0;

        // 初期の値を設定
        $arr = [];
        // 多次元配列
        $subArray = [0];

        // 最後のキーの0番目の値
        $last = $this->get_last_key_value($subArray);

        // 列数
        $column = (int)$input['column'];
        // 丁数
        $piece = $input['completion_data']['completion_piece'] / $column;
        // 印字内容1
        $print_information1 = (int)$input['print_information1'];
        // 印字内容2
        $print_information2 = (int)$input['print_information2'];
        // 仕上げ付丁数
        $completion_piece = (int)$input['completion_data']['completion_piece'];

        $sort = (int)$input['sort'];

        // 足すための１束数
        $temp_a_bundle = $input['completion_data']['completion_piece'] * $input['completion_data']['a_bundle_of_sheet'];

        // breakするフラグを立てる
        $break_flg = false;

        for ($a = 0; $a <= $Total_number_of_bundles_record; $a++) {
            while ($last + $piece <= $print_information2) {
                // 最後のキーの0番目の値＋丁数が印字内容2の値を上回るまでループ
                // 丁数の分だけfor文を回す
                for ($i; $i < $piece; $i++) {
                    // 0番目を印字内容1に置き換える
                    if ($first_arr == 0) {
                        $subArray[0] = $print_information1;
                        $print_information1 += 1;
                    }
                    if ($print_information1 > $print_information2) {
                        $break_flg = true;
                        break;
                    }

                    // 配列に印字内容1を入れていく
                    array_push($subArray, $print_information1);
                    $print_information1 += 1;
                    // 初回1ループ目の配列0番目だけ
                    $first_arr += 1;
                }
                if ($break_flg) {
                    break;
                }

                // 最後のキーの0番目の値
                $last = $this->get_last_key_value($subArray);

                // 逆N字　計算
                $print_information1 = $piece * ($column - 1) + 1;
                $print_information1 += $last;

                // 0に戻す
                $i = 0;

                // 最後のキーの0番目の値＋丁数が印字内容2の値を上回ると強制でbreak
                if ($print_information1 >= $a_bundle + 1) {
                    $a_bundle += $temp_a_bundle;
                    // 多次元配列に新しい配列を追加
                    array_push($arr, $subArray);
                    $subArray = [];
                    break;
                }
            }
            // whileから抜けた残りを配列に追加
            if ($subArray) {
                // 多次元配列に新しい配列を追加
                array_push($arr, $subArray);
                break;
            }
        }

        // 空の配列を作成
        $chunk = array();

        for ($i = 0; $i < count($arr); $i++) {
            // 配列の数
            $count = count($arr[$i]);
            // 配列を一列で設定
            $rowChunk = array_chunk($arr[$i], 1);
            // 丁数の固定値
            $fixed_piece = $completion_piece / $column;
            for ($j = 0; $j < $count; $j++) {
                // 配列の値
                $arr_value = $arr[$i][$j];
                for ($k = 1; $k < $column; $k++) {
                    // 配列の値＋丁数
                    $arr_piece = $arr_value + $piece;

                    // 印字内容2以上になればbreak
                    if ($arr_piece >= $print_information2 + 1) {
                        break;
                    }
                    array_push($rowChunk[$j], $arr_piece);
                    // 固定値を丁数に代入
                    $piece += $fixed_piece;
                }
                // 丁数を元に戻す
                $piece = $piece / $k;
                $k = 1;
            }
            // 配列のマージ
            $chunk = array_merge($chunk, $rowChunk);
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
