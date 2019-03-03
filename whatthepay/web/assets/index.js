$('.modal').modal();

function editAccountPhoto(id) {
    $.ajax({
        url: '/',
        data: 'photo_edit=' + id,
        type: "POST",
        success:function (data) {
            $('.form-div').html(data.view);
            $('#modalEditPhoto').modal('open');
        }
    });
}

function deleteAccountPhoto(id) {
    $.ajax({
        url: '/',
        data: 'photo_delete=' + id,
        type: "POST",
        success: function () {
            location.reload(true);
        }
    });
}

function toggleAccountPhoto(id, value) {
    $.ajax({
        url: '/',
        data: 'photo_toggle[id]=' + id + '&photo_toggle[value]=' + value,
        type: "POST",
        success: function (data) {
            M.toast({html: data.data})
        }
    });
}

$(':checkbox').change(function () {
   toggleAccountPhoto($(this).data('id'), !this.checked);
});