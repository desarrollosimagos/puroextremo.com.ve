   $(document).ready(function(){
    $('#importForm').submit(function() {
    var c = confirm("It will remove your current themeeditor configuration. Are you shure?");
    return c; //you can just return c because it will be true or false
});
      });


function bgToggle(selector)
{
    $(document).ready(function(){
        var control = $("#" + selector + "_bg_type");
        if (control.val() == 1) 
        {
            $("#"+selector+"_bg_image_wrapper").removeClass('hidden');
            $("#"+selector+"_bg_pattern_wrapper").addClass('hidden');
        }

        if (control.val() == 0) {
            $("#"+selector+"_bg_image_wrapper").addClass('hidden');
            $("#"+selector+"_bg_pattern_wrapper").removeClass('hidden');
        }

        $("#" + selector + "_bg_type").change(function() {
            var control = $(this);

             if (control.val() == 2) 
            {
                $("#"+selector+"_bg_image_wrapper").addClass('hidden');
                $("#"+selector+"_bg_pattern_wrapper").addClass('hidden');
            }

            if (control.val() == 1) 
            {
                $("#"+selector+"_bg_image_wrapper").removeClass('hidden');
                $("#"+selector+"_bg_pattern_wrapper").addClass('hidden');
            }

            if (control.val() == 0) {
                $("#"+selector+"_bg_image_wrapper").addClass('hidden');
                $("#"+selector+"_bg_pattern_wrapper").removeClass('hidden');
            }

        });

    });
}