function load_data() {

    $.ajax({
        url: "",
        method: "POST",
        data: { action: 'fetch_data' },
        success: function (data) {
            var html = '';
            for (var count = 0; count < data["id"].length; count++) {
                html += '<tr id="' + data["id"][count] + '">';
                html += '<td>' + data["user"][count] + '</td>';
                html += '<td>' + getGrade(data["grade"][count]) + '</td>';
                html += '</tr>'
            }
            $('tbody').html(html);
        }
    })
}

function getGrade(grade) {
    values = "Stagiaire";
    for (count = 0; count < grade.length; count++) {
        switch (grade[count]) {
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
            default:
                break;
        }
    };
    return values;
}

window.setInterval(load_data, 1000);