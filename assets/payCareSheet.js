$(".btn-danger").on("click", function () {
    $(this).removeClass("btn btn-danger");
    $(this).addClass("bg-success text-light");
    $(this).text("Payée");
    pay(this);

})

function pay(elem) {

    $parent = $(elem).parent();
    $.ajax({
        url: "/lsms/fiche-de-soin/controle",
        method: "POST",
        data: { action: 'pay', id: $($parent).attr('id') },
    })
}