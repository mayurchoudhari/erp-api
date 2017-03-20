$(document).off('change', 'select.export').on('change', 'select.export', function () {
    var this_ref = this;
    var val = $(this).val().split(" ")[1];
    if ($(".breadcrumb li a").html() == 'Costing / details') {
        $("optgroup").show();
        $("optgroup").not("#" + val).hide();
    }
});
$(document).off('change', 'select.domestic').on('change', 'select.domestic', function () {
    var this_ref = this;
    var val = $(this).val().split(" ")[1];
    if ($(".breadcrumb li a").html() == 'Costing / details') {
        $("optgroup").show();
        $("optgroup").not("#" + val).hide();
    }
});
$(document).off('change', 'input.pic').on('change', 'input.pic', function () {
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
$("#recalculate").off("click").on("click", function () {
    add2("fabric", ["consume1", ["div", "fabric", ["wastage1", 100]]], "fconsume1");
    add2("fabric", ["consume2", ["div", "fabric", ["wastage2", 100]]], "fconsume2");
    mul2("fabric", ["fconsume1", "rate1"], "amount1");
    mul2("fabric", ["fconsume2", "rate2"], "amount2");
    add2("accessories", [["div", "accessories", ["aconsume1", "apergarment"]], ["div", "accessories", ["awastage1", 100]]], "afconsume1");
    add2("accessories", [["div", "accessories", ["aconsume2", "apergarment"]], ["div", "accessories", ["awastage2", 100]]], "afconsume2");
    mul2("accessories", ["afconsume1", "arate1"], "aamount1");
    mul2("accessories", ["afconsume2", "arate2"], "aamount2");
    add2("process", [["div", "process", ["pconsume1", "ppergarment"]], ["div", "process", ["pwastage1", 100]]], "pfconsume1");
    add2("process", [["div", "process", ["pconsume2", "ppergarment"]], ["div", "process", ["pwastage2", 100]]], "pfconsume2");
    mul2("process", ["pfconsume1", "prate1"], "pamount1");
    mul2("process", ["pfconsume2", "prate2"], "pamount2");
    var fabamt = 0;
    var accamt = 0;
    var prsamt = 0;
    var expamt = 0;
    $("input.amount1").each(function (i, obj) {
        fabamt = fabamt + Number(obj.value);
    });
    $("input.aamount1").each(function (i, obj) {
        accamt = accamt + Number(obj.value);
    });
    $("input.pamount1").each(function (i, obj) {
        prsamt = prsamt + Number(obj.value);
    });
    $("input.eamount1").each(function (i, obj) {
        expamt = expamt + Number(obj.value);
    });
    var cost1 = fabamt + accamt + prsamt + expamt;
    $("input.cost1").val(cost1.toFixed(2));

    fabamt = 0;
    accamt = 0;
    prsamt = 0;
    expamt = 0;
    $("input.amount2").each(function (i, obj) {
        fabamt = fabamt + Number(obj.value);
    });
    $("input.aamount2").each(function (i, obj) {
        accamt = accamt + Number(obj.value);
    });
    $("input.pamount2").each(function (i, obj) {
        prsamt = prsamt + Number(obj.value);
    });
    $("input.eamount2").each(function (i, obj) {
        expamt = expamt + Number(obj.value);
    });
    var cost2 = fabamt + accamt + prsamt + expamt;
    $("input.cost2").val(cost2.toFixed(2));

    if ($("#rej option:selected").text() == "Cost") {
        var rrate1 = Number($("input.rrate1").val());
        if (rrate1 == 0) {
            $("input.rej1").val("0");
        } else {
            $("input.rej1").val(Number($("input.cost1").val()) * rrate1 * 0.01);
        }
        var rrate2 = Number($("input.rrate2").val());
        if (rrate2 == 0) {
            $("input.rej2").val("0");
        } else {
            $("input.rej2").val(Number($("input.cost2").val()) * rrate2 * 0.01);
        }
    }
    else if ($("#ovr option:selected").text() == "Cost") {
        var orate1 = Number($("input.orate1").val());
        if (orate1 == 0) {
            $("input.rej1").val("0");
        } else {
            $("input.rej1").val(Number($("input.cost1").val()) * orate1 * 0.01);
        }
        var orate2 = Number($("input.orate2").val());
        if (orate2 == 0) {
            $("input.rej2").val("0");
        } else {
            $("input.rej2").val(Number($("input.cost2").val()) * orate2 * 0.01);
        }
    }
    else if ($("#com option:selected").text() == "Cost") {
        var crate1 = Number($("input.crate1").val());
        if (crate1 == 0) {
            $("input.rej1").val("0");
        } else {
            $("input.rej1").val(Number($("input.cost1").val()) * crate1 * 0.01);
        }
        var crate2 = Number($("input.crate2").val());
        if (crate2 == 0) {
            $("input.rej2").val("0");
        } else {
            $("input.rej2").val(Number($("input.cost2").val()) * crate2 * 0.01);
        }
    }
    else {
        $("input.rej1").val("0");
        $("input.rej2").val("0");
    }

    var rej1 = Number($("input.rej1").val());
    if (rej1 == 0) {
        $("input.total1").val($("input.cost1").val());
    } else {
        $("input.total1").val(Number($("input.cost1").val()) + rej1);
    }
    var rej2 = Number($("input.rej2").val());
    if (rej2 == 0) {
        $("input.total2").val($("input.cost2").val());
    } else {
        $("input.total2").val(Number($("input.cost2").val()) + rej2);
    }

    if ($("#rej option:selected").text() == "Total") {
        var rrate1 = Number($("input.rrate1").val());
        if (rrate1 == 0) {
            $("input.ovh1").val("0");
        } else {
            $("input.ovh1").val(Number($("input.total1").val()) * rrate1 * 0.01);
        }
        var rrate2 = Number($("input.rrate2").val());
        if (rrate2 == 0) {
            $("input.ovh2").val("0");
        } else {
            $("input.ovh2").val(Number($("input.total2").val()) * rrate2 * 0.01);
        }
    }
    else if ($("#ovr option:selected").text() == "Total") {
        var orate1 = Number($("input.orate1").val());
        if (orate1 == 0) {
            $("input.ovh1").val("0");
        } else {
            $("input.ovh1").val(Number($("input.total1").val()) * orate1 * 0.01);
        }
        var orate2 = Number($("input.orate2").val());
        if (orate2 == 0) {
            $("input.ovh2").val("0");
        } else {
            $("input.ovh2").val(Number($("input.total2").val()) * orate2 * 0.01);
        }
    }
    else if ($("#com option:selected").text() == "Total") {
        var crate1 = Number($("input.crate1").val());
        if (crate1 == 0) {
            $("input.ovh1").val("0");
        } else {
            $("input.ovh1").val(Number($("input.total1").val()) * crate1 * 0.01);
        }
        var crate2 = Number($("input.crate2").val());
        if (crate2 == 0) {
            $("input.ovh2").val("0");
        } else {
            $("input.ovh2").val(Number($("input.total2").val()) * crate2 * 0.01);
        }
    }
    else {
        $("input.ovh1").val("0");
        $("input.ovh2").val("0");
    }

    var ovh1 = Number($("input.ovh1").val());
    if (ovh1 == 0) {
        $("input.tcost1").val($("input.total1").val());
    } else {
        $("input.tcost1").val(Number($("input.total1").val()) + ovh1);
    }
    var ovh2 = Number($("input.ovh2").val());
    if (ovh2 == 0) {
        $("input.tcost2").val($("input.total2").val());
    } else {
        $("input.tcost2").val(Number($("input.total2").val()) + ovh2);
    }

    if ($("#rej option:selected").text() == "Total Cost") {
        var rrate1 = Number($("input.rrate1").val());
        if (rrate1 == 0) {
            $("input.com1").val("0");
        } else {
            $("input.com1").val(Number($("input.tcost1").val()) * rrate1 * 0.01);
        }
        var rrate2 = Number($("input.rrate2").val());
        if (rrate2 == 0) {
            $("input.com2").val("0");
        } else {
            $("input.com2").val(Number($("input.tcost2").val()) * rrate2 * 0.01);
        }
    }
    else if ($("#ovr option:selected").text() == "Total Cost") {
        var orate1 = Number($("input.orate1").val());
        if (orate1 == 0) {
            $("input.com1").val("0");
        } else {
            $("input.com1").val(Number($("input.tcost1").val()) * orate1 * 0.01);
        }
        var orate2 = Number($("input.orate2").val());
        if (orate2 == 0) {
            $("input.com2").val("0");
        } else {
            $("input.com2").val(Number($("input.tcost2").val()) * orate2 * 0.01);
        }
    }
    else if ($("#com option:selected").text() == "Total Cost") {
        var crate1 = Number($("input.crate1").val());
        if (crate1 == 0) {
            $("input.com1").val("0");
        } else {
            $("input.com1").val(Number($("input.tcost1").val()) * crate1 * 0.01);
        }
        var crate2 = Number($("input.crate2").val());
        if (crate2 == 0) {
            $("input.com2").val("0");
        } else {
            $("input.com2").val(Number($("input.tcost2").val()) * crate2 * 0.01);
        }
    }
    else {
        $("input.com1").val("0");
        $("input.com2").val("0");
    }

    var com1 = Number($("input.com1").val());
    if (com1 == 0) {
        $("input.del1").val($("input.tcost1").val());
    } else {
        $("input.del1").val(Number($("input.tcost1").val()) + com1);
    }
    var com2 = Number($("input.com2").val());
    if (com2 == 0) {
        $("input.del2").val($("input.tcost2").val());
    } else {
        $("input.del2").val(Number($("input.tcost2").val()) + com2);
    }

    if ($("#rej option:selected").text() == "Delivery") {
        var rrate2 = Number($("input.rrate2").val());
        if (rrate2 == 0) {
            $("input.qo").val("0");
        } else {
            $("input.qo").val(Number($("input.del2").val()) * rrate2 * 0.01);
        }
    }
    else if ($("#ovr option:selected").text() == "Delivery") {
        var orate2 = Number($("input.orate2").val());
        if (orate2 == 0) {
            $("input.qo").val("0");
        } else {
            $("input.qo").val(Number($("input.del2").val()) * orate2 * 0.01);
        }
    }
    else if ($("#com option:selected").text() == "Delivery") {
        var crate2 = Number($("input.crate2").val());
        if (crate2 == 0) {
            $("input.qo").val("0");
        } else {
            $("input.qo").val(Number($("input.del2").val()) * crate2 * 0.01);
        }
    }
    else {
        $("input.qo").val("0");
    }
    var qo = Number($("input.qo").val());
    if (qo == 0) {
        $("input.quoted").val($("input.del2").val());
    } else {
        $("input.quoted").val(Number($("input.del2").val()) + qo);
    }

    if ($("#rej option:selected").text() == "Quoted") {
        var rrate2 = Number($("input.rrate2").val());
        if (rrate2 == 0) {
            $("input.cl").val("0");
        } else {
            $("input.cl").val(Number($("input.quoted").val()) * rrate2 * 0.01);
        }
    }
    else if ($("#ovr option:selected").text() == "Quoted") {
        var orate2 = Number($("input.orate2").val());
        if (orate2 == 0) {
            $("input.cl").val("0");
        } else {
            $("input.cl").val(Number($("input.quoted").val()) * orate2 * 0.01);
        }
    }
    else if ($("#com option:selected").text() == "Quoted") {
        var crate2 = Number($("input.crate2").val());
        if (crate2 == 0) {
            $("input.cl").val("0");
        } else {
            $("input.cl").val(Number($("input.quoted").val()) * crate2 * 0.01);
        }
    }
    else {
        $("input.cl").val("0");
    }
    var cl = Number($("input.cl").val());
    if (cl == 0) {
        $("input.closed").val($("input.quoted").val());
    } else {
        $("input.closed").val(Number($("input.quoted").val()) + cl);
    }

    var exr1 = Number($("input.exr1").val());
    if (exr1 == 1) {
        $("input.delc1").val($("input.del1").val());
    } else {
        $("input.delc1").val(Number($("input.del1").val()) / exr1);
    }

    var exr2 = Number($("input.exr2").val());
    if (exr2 == 1) {
        $("input.delc2").val($("input.del2").val());
    } else {
        $("input.delc2").val(Number($("input.del2").val()) / exr2);
    }
});

//Exchange
$(document).off("change", "select.currency").on("change", "select.currency", function (event) {
    var curr = $("select.currency option:selected").text();
    if (curr == "INR") {
        $("input.exr1").val("1");
        $("input.exr2").val("1");
    } else {
        $.get("http://api.fixer.io/latest?symbols=INR&base=" + curr, function (data) {
            // console.log(data.rates.INR);
            $("input.exr1").val(data.rates.INR);
            $("input.exr2").val(data.rates.INR);
        });
    }
});

$(document).off("click","button.addnew").on("click", "button.addnew", function(){
    var cls = $(this).attr("class").split(' ');
    var collection = cls[cls.length - 2];
    var column = cls[cls.length - 1];
    // var collection = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
    var url = "http://138.197.220.205/#/new/add/"+collection+"?field="+this.id+"&col="+column;
    window.open(url, '_blank');
});
// $(document).off("click","button#export").on("click", "button#export", function(){
//     var url = "http://192.168.2.10:4200/#/erp/Masters/Buyers.Export";
//     window.open(url, '_blank');
// });