
import './styles/lsms.scss';
import 'bootstrap';

console.log("#onServiceBtn");

$("#onServiceBtn").on("change", function () {
    $.ajax({
        url: "/lsms/service",
        method: "POST",
        data: { onService: $(this).is(':checked') },
        success: function (data) {
            console.log(data);
        }
    })
})