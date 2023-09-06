<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CSVController extends Controller
{

    public function index()
    {
        // phpinfo();
        return view('csv');
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //今は単純な配列のデータにしているが、実際にはこのデータを作るためにDBから情報を取り出し、配列に入れる処理が必要。
        $data = [
            ['名前', 'ひらがな', '年齢', '性別'],
            ['太郎', 'たろう', '12才', '男'],
            ['花子', 'はなこ', '15才', '女'],
        ];

        $sheet->fromArray($data, null, 'A1');

        // Excelファイルをブラウザからダウンロードする
        $fileName = 'ユーザーリスト' . date('Y_m_d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
