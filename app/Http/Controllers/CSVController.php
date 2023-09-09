<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CSVController extends Controller
{
    public function index()
    {
        return view('csv');
    }

    // CSVファイルダウンロード
    public function download(Request $request)
    {
        // sessionから変数を取り出す
        $chunk = $request->session()->get('csv_chunk');

        if ($chunk != null) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            // スプレッドシートにデータを追加します。
            $sheet->fromArray($chunk, null, 'A1');

            // Excelダウンロード用の適切なヘッダーを設定します。
            $fileName = 'ユーザーリスト' . date('Y_m_d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename=\"{$fileName}\"");
            header('Cache-Control: max-age=0');

            // Excelライターを作成し、ファイルを出力ストリームに保存します。
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

            // session削除
            $request->session()->forget('csv_chunk');
            exit;
        }

        return redirect(route('index'));
    }
}
