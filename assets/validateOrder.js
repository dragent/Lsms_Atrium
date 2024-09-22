$(".btn-dark").on("click", function () {
    $(this).removeClass("btn btn-dark");
    var d = new Date();

    var month = d.getMonth() + 1;
    var day = d.getDate();

    var date = (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') + month + '/' + d.getFullYear();
    $(this).parent().html(date);
    pay(this, date);

})

function pay(elem) {
    $.ajax({
        url: "",
        method: "POST",
        data: { action: 'receive', id: $(elem).val() },
    })
}