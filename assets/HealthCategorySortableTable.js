import 'jquery-ui/ui/widget';
import 'jquery-ui/ui/widgets/sortable';

function load_data() {
    $.ajax({
        url: "categorie-soins/modification",
        method: "POST",
        data: { action: 'fetch_data' },
        success: function (data) {
            var html = '';
            for (var count = 0; count < data.length; count++) {
                html += '<tr id="' + data[count].id + '">';
                html += '<td>' + data[count].name + '</td>';
                html += '<td> <a href="categorie-soins/suppression/' + data[count].slug + '"><i class="fs-5 bi-trash3-fill" ></i></a>'
                html += '</tr>'
            }
            $('tbody').html(html);
        }
    })
}

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

        })
    }
});


load_data();