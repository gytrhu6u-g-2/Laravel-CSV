
// 仕上げ選択で入力項目の変更
var completion = document.getElementById("completion");
var select_value_container = document.getElementsByClassName("select_value_container")[0];

completion.onchange = event => {
    var select_value = event.target.value;
    var select__completion = document.getElementsByClassName("select__completion");

    if (select__completion[0])
    {
        select__completion[0].remove();
    }

    if (select_value == 1)
    {
        var html = `
        <div class="row g-2 mx-2 select__completion">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="completion_piece">仕上げ付丁数:</label>
                    <input type="number" id="completion_piece" name="completion_piece"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="a_bundle_of_sheet">一束のシート数:</label>
                    <input type="number" id="a_bundle_of_sheet" name="a_bundle_of_sheet"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="print_column">印刷列数:</label>
                    <input type="number" id="print_column" name="print_column"
                        class="form-control">
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
                    <label for="max_base_sheet">原紙最大巻ｍ数:</label>
                    <input type="number" id="max_base_sheet" name="max_base_sheet"
                        class="form-control">
                </div>
            </div>
        </div>
        `;

        select_value_container.insertAdjacentHTML('afterbegin', html);
    }
    else if(select_value == 2)
    {
        var html = `
        <div class="row g-2 mx-2 select__completion">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="roll_num">巻き枚数:</label>
                    <input type="number" id="roll_num" name="roll_num"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="lead_inside">リード紙巻芯:</label>
                    <input type="number" id="lead_inside" name="lead_inside"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="lead_outside">リード紙巻外:</label>
                    <input type="number" id="lead_outside" name="lead_outside"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="print_column">印刷列数:</label>
                    <input type="number" id="print_column" name="print_column"
                        class="form-control">
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
                    <label for="max_base_sheet">原紙最大巻ｍ数:</label>
                    <input type="number" id="max_base_sheet" name="max_base_sheet"
                        class="form-control">
                </div>
            </div>
        </div>
        `;

        select_value_container.insertAdjacentHTML('afterbegin', html);
    }
    else if (select_value == 3)
    {
        var html = `
        <div class="row g-2 mx-2 select__completion">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="completion_piece">仕上げ付丁数:</label>
                    <input type="number" id="completion_piece" name="completion_piece"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="a_bundle_of_fold">一束の折り数:</label>
                    <input type="number" id="a_bundle_of_fold" name="a_bundle_of_fold"
                        class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="print_column">印刷列数:</label>
                    <input type="number" id="print_column" name="print_column"
                        class="form-control">
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
                    <label for="max_base_sheet">原紙最大巻ｍ数:</label>
                    <input type="number" id="max_base_sheet" name="max_base_sheet"
                        class="form-control">
                </div>
            </div>
        </div>
        `;

        select_value_container.insertAdjacentHTML('afterbegin', html);
    }
    else 
    {
        if (select__completion[0])
        {
            select__completion[0].remove();
        }
    }
}