$(document).ready(function () {
    $('body').enableDragging();


    //Init - text
    changeSliderText();

    $(".titleInputWrap:visible .titleInput,  .subtitleInputWrap:visible .subtitleInput, .descInputWrap:visible .descInput").change(function () {
        changeSliderText();
    });





    $(".language_flags img").on("click", function () {
        changeSliderText();
        $(".titleInputWrap:visible .titleInput,  .subtitleInputWrap:visible .subtitleInput, .descInputWrap:visible .descInput").change(function () {
            changeSliderText();
        });
    });

    //Init - text colors
    changeSliderColor();


    // Text Before animation:
    v_left = $("#b_text_left").val();
    v_top = $("#b_text_top").val();
    v_rotation = 'rotate(' + $("#b_text_rotation").val() + 'deg)';



    $("#before_text").css({
        '-webkit-transform': v_rotation,
        '-moz-transform': v_rotation,
        '-ms-transform': v_rotation,
        '-o-transform': v_rotation,
        'transform': v_rotation,
        'top': v_top + "%",
        'left': v_left + "%"
    });



    $("#b_text_left").change(function () {
        $("#before_text").css({
            'left': $(this).val() + "%"
        });
    });

    $("#b_text_top").change(function () {
        $("#before_text").css({
            'top': $(this).val() + "%"

        });

    });

    $("#b_text_rotation").change(function () {


        v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
        $("#before_text").css({
            '-webkit-transform': v_rotation2,
            '-moz-transform': v_rotation2,
            '-ms-transform': v_rotation2,
            ' -o-transform': v_rotation2,
            'transform': v_rotation2
        });
    });


    // Showed elements:

    v_left = $("#text_left").val();
    v_top = $("#text_top").val();
    v_rotation = 'rotate(' + $("#text_rotation").val() + 'deg)';



    $("#showed_text").css({
        '-webkit-transform': v_rotation,
        '-moz-transform': v_rotation,
        '-ms-transform': v_rotation,
        '-o-transform': v_rotation,
        'transform': v_rotation,
        'top': v_top + "%",
        'left': v_left + "%"
    });



    $("#text_left").change(function () {
        $("#showed_text").css({
            'left': $(this).val() + "%"
        });
    });

    $("#text_top").change(function () {
        $("#showed_text").css({
            'top': $(this).val() + "%"

        });

    });

    $("#text_rotation").change(function () {


        v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
        $("#showed_text").css({
            '-webkit-transform': v_rotation2,
            '-moz-transform': v_rotation2,
            '-ms-transform': v_rotation2,
            '-o-transform': v_rotation2,
            'transform': v_rotation2
        });
    });





    // After animation:
    v_left = $("#a_text_left").val();
    v_top = $("#a_text_top").val();
    v_rotation = 'rotate(' + $("#a_text_rotation").val() + 'deg)';



    $("#after_text").css({
        '-webkit-transform': v_rotation,
        '-moz-transform': v_rotation,
        '-ms-transform': v_rotation,
        ' -o-transform': v_rotation,
        'transform': v_rotation,
        'top': v_top + "%",
        'left': v_left + "%"
    });



    $("#a_text_left").change(function () {
        $("#after_text").css({
            'left': $(this).val() + "%"
        });
    });

    $("#a_text_top").change(function () {
        $("#after_text").css({
            'top': $(this).val() + "%"

        });

    });

    $("#a_text_rotation").change(function () {


        v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
        $("#after_text").css({
            '-webkit-transform': v_rotation2,
            '-moz-transform': v_rotation2,
            '-ms-transform': v_rotation2,
            ' -o-transform': v_rotation2,
            'transform': v_rotation2
        });
    });





    //Init - images
	initImages();

});





(function ($) {
    $.fn.enableDragging = function () {
        $(".dragElement").draggable({
            cursor: "move",
            handle: ".dragHandle",
            stop: function (event, ui) {
                var leftval = parseInt($(this).css("left")) / ($(this).parent().width() / 100);
                var topval = parseInt($(this).css("top")) / ($(this).parent().height() / 100);

                $(this).css("left", leftval + "%");
                $(this).css("top", topval + "%");

                var atype = $(this).attr("data-atype");
                var imageid = $(this).attr("data-imageid");

                switch (atype) {
                case "before":
                    inputNameLeft = "b_image_" + imageid + "_left";
                    inputNameTop = "b_image_" + imageid + "_top";
                    break;
                case "showed":
                    inputNameLeft = "image_" + imageid + "_left";
                    inputNameTop = "image_" + imageid + "_top";
                    break;
                case "after":
                    inputNameLeft = "a_image_" + imageid + "_left";
                    inputNameTop = "a_image_" + imageid + "_top";
                    break;
                }
                $("#" + inputNameLeft).val(leftval);
                $("#" + inputNameTop).val(topval);




            }

        });


        $('.rotateButton').draggable({
            opacity: 0.01,
            helper: 'clone',
            drag: function (event, ui) {
                var rotateCSS = 'rotate(' + ui.position.left + 'deg)';

                $(this).parent().css({
                    '-webkit-transform': rotateCSS,
                    '-moz-transform': rotateCSS,
                    '-ms-transform': rotateCSS,
                    ' -o-transform': rotateCSS,
                    'transform': rotateCSS
                });
            },

            stop: function (event, ui) {
                var rotationval = ui.position.left;

                var atype = $(this).parent().attr("data-atype");
                var imageid = $(this).parent().attr("data-imageid");

                switch (atype) {
                case "before":
                    inputNameRotate = "b_image_" + imageid + "_rotation";
                    break;
                case "showed":
                    inputNameRotate = "image_" + imageid + "_rotation";
                    break;
                case "after":
                    inputNameRotate = "a_image_" + imageid + "_rotation";
                    break;
                }
                $("#" + inputNameRotate).val(rotationval);
            }

        });
    };
})(jQuery);




$(function () {
    $("#tabs_animation").tabs();
});



$(function () {

    $(".dragTextElement").draggable({
        cursor: "move",
        handle: ".dragHandle",
        stop: function (event, ui) {
            var leftval = parseInt($(this).css("left")) / ($(this).parent().width() / 100);
            var topval = parseInt($(this).css("top")) / ($(this).parent().height() / 100);

            $(this).css("left", leftval + "%");
            $(this).css("top", topval + "%");

            var atype = $(this).attr("data-atype");


            switch (atype) {
            case "before":
                inputNameLeft = "b_text_left";
                inputNameTop = "b_text_top";
                break;
            case "showed":
                inputNameLeft = "text_left";
                inputNameTop = "text_top";
                break;
            case "after":
                inputNameLeft = "a_text_left";
                inputNameTop = "a_text_top";
                break;
            }
            $("#" + inputNameLeft).val(leftval);
            $("#" + inputNameTop).val(topval);




        }

    });


    $('.rotateButton2').draggable({
        opacity: 0.01,
        helper: 'clone',
        drag: function (event, ui) {
            var rotateCSS = 'rotate(' + ui.position.left + 'deg)';

            $(this).parent().css({
                '-webkit-transform': rotateCSS,
                '-moz-transform': rotateCSS,
                '-ms-transform': rotateCSS,
                ' -o-transform': rotateCSS,
                'transform': rotateCSS
            });
        },

        stop: function (event, ui) {
            var rotationval = ui.position.left;

            var atype = $(this).parent().attr("data-atype");


            switch (atype) {
            case "before":
                inputNameRotate = "b_text_rotation";
                break;
            case "showed":
                inputNameRotate = "text_rotation";
                break;
            case "after":
                inputNameRotate = "a_text_rotation";
                break;
            }
            $("#" + inputNameRotate).val(rotationval);
        }

    });

});

function changeSliderColor() {
    h_color = $("#h_color").val();
    h_bg = $("#h_bg").val();
    d_color = $("#d_color").val();
    d_bg = $("#d_bg").val();

    $(".textWrapper h3, .textWrapper h2").css({
        'color': h_color,
        'background-color': h_bg
    });
    $(".textWrapper .desc").css({
        'color': d_color,
        'background-color': d_bg
    });
}



function changeSliderText() {
    h2_text = $(".titleInputWrap:visible .titleInput").val();
    h3_text = $(".subtitleInputWrap:visible .subtitleInput").val();
    desc_text = $(".descInputWrap:visible .descInput").val();

    if ((h2_text == '') && (h3_text == '') && (desc_text == '')) {
        $(".textWrapper").hide();

    } else {
        $(".textWrapper").show();
        $(".textWrapper h2").text(h2_text);
        $(".textWrapper h3").text(h3_text);
        $(".textWrapper .desc").text(desc_text);
    }


}

function initImages(){    $(".slideImage").each(function (index) {

        var imageid = $(this).attr("data-imageid");

        // Before animation:
        v_left = $("#b_image_" + imageid + "_left").val();
        v_top = $("#b_image_" + imageid + "_top").val();
        v_rotation = 'rotate(' + $("#b_image_" + imageid + "_rotation").val() + 'deg)';



        $("#before_image_" + imageid).css({
            '-webkit-transform': v_rotation,
            '-moz-transform': v_rotation,
            '-ms-transform': v_rotation,
            ' -o-transform': v_rotation,
            'transform': v_rotation,
            'top': v_top + "%",
            'left': v_left + "%"
        });



        $("#b_image_" + imageid + "_left").change(function () {
            $("#before_image_" + imageid).css({
                'left': $(this).val() + "%"
            });
        });

        $("#b_image_" + imageid + "_top").change(function () {
            $("#before_image_" + imageid).css({
                'top': $(this).val() + "%"

            });

        });

        $("#b_image_" + imageid + "_rotation").change(function () {


            v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
            $("#before_image_" + imageid).css({
                '-webkit-transform': v_rotation2,
                '-moz-transform': v_rotation2,
                '-ms-transform': v_rotation2,
                ' -o-transform': v_rotation2,
                'transform': v_rotation2
            });
        });


        // Showed elements:


        v_left = $("#image_" + imageid + "_left").val();
        v_top = $("#image_" + imageid + "_top").val();
        v_rotation = 'rotate(' + $("#image_" + imageid + "_rotation").val() + 'deg)';



        $("#showed_image_" + imageid).css({
            '-webkit-transform': v_rotation,
            '-moz-transform': v_rotation,
            '-ms-transform': v_rotation,
            ' -o-transform': v_rotation,
            'transform': v_rotation,
            'top': v_top + "%",
            'left': v_left + "%"
        });



        $("#image_" + imageid + "_left").change(function () {
            $("#showed_image_" + imageid).css({
                'left': $(this).val() + "%"
            });
        });

        $("#image_" + imageid + "_top").change(function () {
            $("#showed_image_" + imageid).css({
                'top': $(this).val() + "%"

            });

        });

        $("#image_" + imageid + "_rotation").change(function () {


            v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
            $("#showed_image_" + imageid).css({
                '-webkit-transform': v_rotation2,
                '-moz-transform': v_rotation2,
                '-ms-transform': v_rotation2,
                ' -o-transform': v_rotation2,
                'transform': v_rotation2
            });
        });





        // After animation:
        v_left = $("#a_image_" + imageid + "_left").val();
        v_top = $("#a_image_" + imageid + "_top").val();
        v_rotation = 'rotate(' + $("#a_image_" + imageid + "_rotation").val() + 'deg)';



        $("#after_image_" + imageid).css({
            '-webkit-transform': v_rotation,
            '-moz-transform': v_rotation,
            '-ms-transform': v_rotation,
            ' -o-transform': v_rotation,
            'transform': v_rotation,
            'top': v_top + "%",
            'left': v_left + "%"
        });



        $("#a_image_" + imageid + "_left").change(function () {
            $("#after_image_" + imageid).css({
                'left': $(this).val() + "%"
            });
        });

        $("#a_image_" + imageid + "_top").change(function () {
            $("#after_image_" + imageid).css({
                'top': $(this).val() + "%"

            });

        });

        $("#a_image_" + imageid + "_rotation").change(function () {


            v_rotation2 = 'rotate(' + $(this).val() + 'deg)';
            $("#after_image_" + imageid).css({
                '-webkit-transform': v_rotation2,
                '-moz-transform': v_rotation2,
                '-ms-transform': v_rotation2,
                ' -o-transform': v_rotation2,
                'transform': v_rotation2
            });
        });


    });}