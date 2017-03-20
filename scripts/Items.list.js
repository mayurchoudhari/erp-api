function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            if ($("#image").length == 0) {
                $('input.pic').parent().parent().append('<img id="image" src="' + e.target.result + '" />');
            } else {
                $("#image").attr('src', e.target.result);
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).off('change', 'input.pic').on('change', 'input.pic', function () {
    readURL(this);
    var this_ref = this;
    if ($(".breadcrumb li a").html() == 'Costing / details') {
        var fileSelect = document.getElementsByClassName("pic")[0];
        var file = fileSelect.files[0];
        var name = file.name;
        var formData = new FormData();
        formData.append('file', file, file.name);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                name = JSON.parse(xhr.responseText)['name']; //Outputs a DOMString by default
                document.getElementsByClassName("pic")[1].value = name;
                //console.log(name);console.log(JSON.parse(xhr.responseText));
            }
        }
        xhr.open('POST', 'http://138.197.220.205:3002/upload', true);
        xhr.send(formData);
    }
});