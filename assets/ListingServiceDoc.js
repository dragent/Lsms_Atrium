function load_data() {

    $.ajax({
        url: "",
        method: "POST",
        data: { action: 'fetch_data' },
        success: function (data) {
            var html = '';
            for (var count = 0; count < data.length; count++) {
                html += '<tr id="' + data[count].id + '">';
                html += '<td>' + data[count].Username + '</td>';
                html += '<td>' + getGrade(data[count].roles) + '</td>';
                html += '</tr>'
            }
            $('tbody').html(html);
        }
    })
}

function getGrade(roles) {
    value = "Stagiaire";
    roles.forEach(role => {
        switch (role) {
            case "ROLE_DIRECTION":
                values = "Directeur";
                break;
            case "ROLE_MEDECIN_CHEF":
                values = "Chef de Service";
                break;
            case "ROLE_MEDECIN_CHEF":
                values = "Médecin en chef";
                break;
            case "ROLE_MEDECIN_FORMATEUR":
                values = "Médecin Formateur";
                break;
            case "ROLE_MEDECIN_NOVICE":
                values = "Médecin Novice";
                break;
            case "ROLE_AMBULANCIER":
                values = "Ambulancier";
                break;
        }
    });
    return values;
}

window.setInterval(load_data, 1000);