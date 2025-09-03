jQuery(document).ready(function($) {
    let mediaUploader;

    $('#whp_upload_file_button').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Whitepaper File',
            button: {
                text: 'Choose File'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#whp_download_link').val(attachment.url);
            $('#whp_remove_file_button').show();
        });

        mediaUploader.open();
    });

    $('#whp_remove_file_button').on('click', function(e) {
        e.preventDefault();
        $('#whp_download_link').val('');
        $(this).hide();
    });
});
