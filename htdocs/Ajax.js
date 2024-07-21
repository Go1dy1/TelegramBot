$(function () {
    $('#ajax-form').on('submit', function (e) {
        e.preventDefault();
        let data = new FormData(this);

        $.each($('#file')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });


        $.ajax({
            url: 'Execute.php',
            data: data,
            dataType: 'json',
            cache: false,
            method: 'POST',
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);
            }
        });
    });
})
