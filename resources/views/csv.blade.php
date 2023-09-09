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
        @if (isset($chunk))
        <h4 class="ms-3">配列を受け取りました</h4>
        @endif
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
                                <label for="column">仕上げ列数:</label>
                                <input type="number" id="column" name="column" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="process">工程数:</label>
                                <input type="number" id="process" name="process" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="process">数量:</label>
                                <input type="number" id="num" name="num" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="process">印字内容:</label>
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

generate.addEventListener("click", function(){
    generateArr();
});

// CSV配列生成処理
async function generateArr()
{
    var completion = document.getElementById("completion").value;
    var floating = document.getElementById("floating").value;
    var sort = document.getElementById("sort").value;
    var column = document.getElementById("column").value;
    var process = document.getElementById("process").value;
    var num = document.getElementById("num").value;
    var print_information1 = document.getElementById("print_information1").value;
    var print_information2 = document.getElementById("print_information2").value;

    var data = {
        completion: completion,
        floating: floating,
        sort: sort,
        column: column,
        process: process,
        num: num,
        print_information1: print_information1,
        print_information2: print_information2
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