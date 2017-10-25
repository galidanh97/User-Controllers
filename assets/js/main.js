//Toastr option
toastr.options.closeButton = true;
toastr.options.newestOnTop = false;
toastr.options.progressBar = true;

$ = jQuery;
$(document).ready(function(){
    jQuery(".uc_form  .input").focusin(function () {
        jQuery(this).find("span").animate({"opacity": "0"}, 200);
    });

    jQuery(".uc_form .input").focusout(function () {
        jQuery(this).find("span").animate({"opacity": "1"}, 300);
    });


    jQuery(".uc_form.login").submit(function (e) {
        e.preventDefault();
        $(this).find(".submit i").removeAttr('class').addClass("fa fa-refresh fa-spin").css({"color": "#fff"});
        $(".submit").css({"background": "#2ecc71", "border-color": "#2ecc71"});
        $("input").css({"border-color": "#2ecc71"});

        _action = $(this).attr("action");
        $.ajax({
            type: "POST",
            url: _action,
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                if ( data.success === true) {
                    $(".feedback").html(data.message);
                    $(".feedback").show().animate({"opacity": "1",}, 400);
                    window.setTimeout(window.location.href = '/',3000);
                }
                else {
                    $(".feedback").html(data.message);
                    $(".feedback").show().animate({"opacity": "1"}, 400).css({"background":"#FF7052"});
                    $(".uc_form .submit i").removeAttr('class').addClass("fa fa-long-arrow-right");//.css({"color": "#FF7052", "background":"#fff"});
                }
            }
        });
        return false;
    });

    jQuery(".uc_form.register").submit(function (e) {
        e.preventDefault();
        $(this).find(".submit i").removeAttr('class').addClass("fa fa-refresh fa-spin").css({"color": "#fff"});
        $(".submit").css({"background": "#2ecc71", "border-color": "#2ecc71"});
        $("input").css({"border-color": "#2ecc71"});

        _action = $(this).attr("action");
        $.ajax({
            type: "POST",
            url: _action,
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                if ( data.success === true) {
                    $(".feedback").html(data.message);
                    $(".feedback").show().animate({"opacity": "1",}, 400);
                    window.setTimeout(window.location.href = '/login',3000);
                }
                else {
                    $(".feedback").html(data.message);
                    $(".feedback").show().animate({"opacity": "1"}, 400).css({"background":"#FF7052"});
                    $(".uc_form .submit i").removeAttr('class').addClass("fa fa-long-arrow-right");//.css({"color": "#FF7052", "background":"#fff"});
                    grecaptcha.reset();

                }
            }
        });
        return false;
    });


    jQuery(".uc_form.profile").submit(function (e) {
        e.preventDefault();
        var loading = toastr.info("Updating information....", "Processing", {timeOut:50000, extendedTimeOut:5000});
        //Check input data before send to server
        $(this).find(".submit i").removeAttr('class').addClass("fa fa-refresh fa-spin").css({"color": "#fff"});
        $(".submit").css({"background": "#2ecc71", "border-color": "#2ecc71"});
        $("input").css({"border-color": "#2ecc71"});

        _action = $(this).attr("action");
        $(".input-error").html("").hide();
        $.ajax({
            type: "POST",
            url: _action,
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                if (data.success === true) {
                    toastr.success(data.message);
                }
                else {
                    $.each(data.message, function (index, value) {
                        $("#" + index + " .input-error").html(value);
                        $("#" + index + " .input-error").show();
                        $("#" + index + " input").css({"border-color": "#cc2d07"});
                    });

                    toastr.error("Information updated successfully!")
                }
            },
            complete: function (e) {
                grecaptcha.reset();
                $(".uc_form .submit i").removeAttr('class').addClass("fa fa-long-arrow-right");
            }
        });
        loading.remove();
        return false;
    });


})

jQuery(document).ready(function(){

    jQuery('#photoimg').on('change', function()
    {
        var loading = toastr.info("Updating Avatar....", "Loading", {timeOut:50000, extendedTimeOut:5000});
        var formData = new FormData($("#cropimage")[0]);

        $.ajax({
            url: $("#cropimage").attr("action"),
            type: "POST",
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            dataType: "json",
            success: function(data)
            {
                if (data.success === true) {
                    $("#avatar-edit-img").attr("src", data.message.img);
                    toastr.success("Avatar updated successfully!!");
                    if(data.redirect !== false)
                        window.location.replace(data.redirect);
                }
                else{

                    toastr.error(data.message);
                    if(data.redirect !== false)
                        window.location.replace(data.redirect);
                }
            },
            complete : function(){
                loading.remove();
            }
        });
    });

});
