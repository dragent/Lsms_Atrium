$('#myModal').on('shown.bs.modal', function () {
    console.log("test");
    $('#myInput').trigger('focus')
})