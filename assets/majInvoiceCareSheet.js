function calculInvoice() {
    let total = 0;
    $("input:text").each(function () {
        let $quantityInput = $(this).val();
        console.log($quantityInput);
        $price = $("#price-" + $(this).attr("id").split("quantity-")[1])[0].outerText;
        $price = $price.split("$")[0];
        total += $price * $quantityInput;
        console.log(total);
    })

    if ($("#distanceSwitch").is(":checked")) {
        total += 10;
    }
    $("#Invoice").text(total);
}
$("input").on("change", function () {
    calculInvoice();
});

$("#distanceSwitch").on("change", function () {
    let $total = $("#Invoice").text();
    if ($("#distanceSwitch").is(":checked"))
        $total = parseInt($total) + 10;
    else
        $total -= 10;
    $("#Invoice").text($total);
})

$("#distanceSwitch").prop("checked", false)