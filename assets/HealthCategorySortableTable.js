import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/sortable';


$('tbody').sortable({
    placeholder: "ui-state-highlight",
    update: function (event, ui) {
        var position = new Array();
        $('tbody.ui-sortable tr').each(function () {
            position.push($(this).attr('id'));
        });
        $.ajax({
            url: "categorie-soins/modification",
            method: "POST",
            data: { position: position, action: 'update' },
            success: function (data) {
                console.log(data);
            }
        })
    }
});


