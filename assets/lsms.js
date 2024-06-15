
import './styles/lsms.scss';
import 'bootstrap';


$("#onServiceBtn").on("change", function () {

    $.ajax({
        url: "/lsms/service",
        method: "POST",
        data: { onService: $(this).is(':checked') },
        success: function () {
            if ($("#onServiceBtn").is(":checked")) {
                $("#onServiceLabel").addClass("text-success");
                $("#onServiceLabel").html("<b>En service</b>");
            }
            else {
                $("#onServiceLabel").removeClass("text-success");
                $("#onServiceLabel").html("<b>Hors service</b>");
            }
        }
    })
})

