$(document).ready(function() {
    
    $(".add-file-ui").click(function(){ 

        var lsthmtl = $(".clone").html();

        $(".increment").after(lsthmtl);

    });

    $("body").on("click",".remove-file-ui",function(){ 

        $(this).parents(".hdtuto").remove();

    });

});