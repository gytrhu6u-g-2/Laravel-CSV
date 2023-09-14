<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>
    <div class="hole__container">
        <div class="container border border-primary input__container">
            <h3 class="text-center mt-3">入力項目</h3>
            <div class="container input__items">
                <div class="input__items__container">
                    <div class="row g-2 mx-2">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="completion">仕上げ:</label>
                                <select class="form-select" aria-label="Default select example" name="completion"
                                    id="completion">
                                    <option selected value="">未選択</option>
                                    <option value="1">シート</option>
                                    <option value="2">巻き</option>
                                    <option value="3">折り</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="floating">流れ方向:</label>
                                <select class="form-select" aria-label="Default select example" name="floating"
                                    id="floating">
                                    <option selected value="">未選択</option>
                                    <option value="1">Z字</option>
                                    <option value="2">逆N字</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="sort">昇順・降順:</label>
                                <select class="form-select" aria-label="Default select example" name="sort" id="sort">
                                    <option selected value="">未選択</option>
                                    <option value="1">昇順</option>
                                    <option value="2">降順</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="direction">天地・左右:</label>
                                <select class="form-select" aria-label="Default select example" name="direction"
                                    id="direction">
                                    <option selected value="">未選択</option>
                                    <option value="1">天地</option>
                                    <option value="2">左右</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="column">仕上げ列数:</label>
                                <input type="number" id="column" name="column" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="num">数量:</label>
                                <input type="number" id="num" name="num" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="each_page">各枚数:</label>
                                <input type="number" id="each_page" name="each_page" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="how_to_add">付け方:</label>
                                <input type="number" id="how_to_add" name="how_to_add" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="size">サイズ:</label>
                                <div class="d-flex print_information_input">
                                    <input type="number" id="size1" name="size1" class="form-control me-1">
                                    <span class="d-flex align-items-center">×</span>
                                    <input type="number" id="size2" name="size2" class="form-control ms-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="send_designation">セパ巾送り指定:</label>
                                <div class="d-flex print_information_input">
                                    <input type="number" id="send_designation1" name="send_designation1"
                                        class="form-control me-1">
                                    <span class="d-flex align-items-center">×</span>
                                    <input type="number" id="send_designation2" name="send_designation2"
                                        class="form-control ms-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="print_information">印字内容:</label>
                                <div class="d-flex print_information_input">
                                    <input type="number" id="print_information1" name="print_information1"
                                        class="form-control me-3">
                                    <span class="d-flex align-items-center">〜</span>
                                    <input type="number" id="print_information2" name="print_information2"
                                        class="form-control ms-3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="select_value_container">

                    </div>
                </div>
                <div class="container-fluid d-flex justify-content-center mt-4 mb-2 p-0 csvBtn__container">
                    <button type="button" class="btn btn-primary me-3 generate">CSV配列生成</button>
                    <form action="{{ route('download') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary ms-3">ダウンロード</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    </form>
                </div>
            </div>
        </div>

</body>
<script>
    // ダウンロード時に使う配列
    var csvArray = [];
    // CSV配列生成ボタン
    var generate = document.querySelector(".generate");

    generate.addEventListener("click", function() {
        generateArr();
    });

    // CSV配列生成処理
    async function generateArr() {
        // 仕上げ
        var completion = document.getElementById("completion").value;
        // 流れ方向
        var floating = document.getElementById("floating").value;
        // 昇順・降順
        var sort = document.getElementById("sort").value;
        // 列数
        var column = document.getElementById("column").value;
        // 数量
        var num = document.getElementById("num").value;
        // 天地・左右
        var direction = document.getElementById("direction").value;
        // 各枚数
        var each_page = document.getElementById("each_page").value;
        // 付け方
        var how_to_add = document.getElementById("how_to_add").value;
        // サイズ１（巾）
        var size1 = document.getElementById("size1").value;
        // サイズ２（送り(単位)mm）
        var size2 = document.getElementById("size2").value;
        // セパ巾送り指定１（セパ巾）
        var send_designation1 = document.getElementById("send_designation1").value;
        // セパ巾送り指定２（送り指定 (単位)mm）
        var send_designation2 = document.getElementById("send_designation2").value;


        // 印字内容
        var print_information1 = document.getElementById("print_information1").value;
        var print_information2 = document.getElementById("print_information2").value;

        // 仕上げ丁数
        var completion_piece = document.getElementById("completion_piece");
        // 一束のシート数
        var a_bundle_of_sheet = document.getElementById("a_bundle_of_sheet");
        // 印刷列数
        var print_column = document.getElementById("print_column");
        // 工程数
        var process = document.getElementById("process");
        // 原紙最大巻ｍ数
        var max_base_sheet = document.getElementById("max_base_sheet");

        // 巻き枚数
        var roll_num = document.getElementById("roll_num");
        // リード紙巻芯
        var lead_inside = document.getElementById("lead_inside");
        // リード紙巻外
        var lead_outside = document.getElementById("lead_outside");

        // 一束の折り数
        var a_bundle_of_fold = document.getElementById("a_bundle_of_fold");

        if (completion == 1) {
            var completion_data = {
                completion_piece: completion_piece.value,
                a_bundle_of_sheet: a_bundle_of_sheet.value,
                print_column: print_column.value,
                process: process.value,
                max_base_sheet: max_base_sheet.value
            }
        } else if (completion == 2) {
            var completion_data = {
                roll_num: roll_num.value,
                lead_inside: lead_inside.value,
                lead_outside: lead_outside.value,
                print_column: print_column.value,
                process: process.value,
                max_base_sheet: max_base_sheet.value
            }
        } else if (completion == 3) {
            var completion_data = {
                completion_piece: completion_piece.value,
                a_bundle_of_fold: a_bundle_of_fold.value,
                print_column: print_column.value,
                process: process.value,
                max_base_sheet: max_base_sheet.value
            }
        }


        var data = {
            completion: completion,
            floating: floating,
            sort: sort,
            column: column,
            num: num,
            direction: direction,
            // 各枚数
            each_page: each_page,
            // 付け方
            how_to_add: how_to_add,
            // サイズ１（巾）
            size1: size1,
            // サイズ２（送り(単位)mm）
            size2: size2,
            // セパ巾送り指定１（セパ巾）
            end_designation1: send_designation1,
            // セパ巾送り指定２（送り指定 (単位)mm）
            send_designation2: send_designation2,


            completion_data: completion_data,
            print_information1: print_information1,
            print_information2: print_information2,
        }

        try {
            const url = "{{ route('generateArr') }}";
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data)
            })
            const result = await response.json();
            csvArray = result;
            console.log(csvArray);
        } catch (error) {
            console.log(error);
            alert(`通信に失敗しました。\r\n ${error}`);
        }
    }
</script>

</html>