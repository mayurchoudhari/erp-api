$("input.city").keyup(function(){
    var this_ref = this;
    if($(".breadcrumb li a").html() == 'masters / city'){
        if($(this_ref).hasClass("ng-invalid")){
            $(this_ref).css("border-left-width","5px");
            $(this_ref).css("border-left-color","red");
        }
    }
});