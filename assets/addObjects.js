const $recipeItem = $("#add_object_quantitiesComponent")
let inc = 0;
$("#addBtn").on("click", function () {
    if ($("#rmvBtn").hasClass("d-none")) {
        $("#rmvBtn").removeClass("d-none");

    }
    $prototype = $($recipeItem).data("prototype");
    $($recipeItem).append($prototype.replace(/__name__/g, "component-" + inc));
    inc++;
});

$("#rmvBtn").on("click", function () {
    if ($recipeItem.length > 0) {
        $prototype = $($recipeItem).data("prototype");
        $recipeItem.children().last().remove();
        inc--;
    }
    if ($($recipeItem).children().length == 0) {
        $("#rmvBtn").addClass("d-none");
    }
});