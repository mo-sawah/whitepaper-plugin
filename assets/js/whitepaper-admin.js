jQuery(document).ready(function($) {
    // Initialize the WordPress color picker
    $('.whp-color-picker').wpColorPicker({
        // A callback function that fires when the color is changed.
        change: function(event, ui) {
            // Get the new color
            var newColor = ui.color.toString();
            // Get the ID of the input field
            var inputId = $(this).attr('name');

            // Update the live preview
            updatePreview(inputId, newColor);
        },
        // A callback function that fires when the color picker is cleared.
        clear: function() {
            var inputId = $(this).attr('name');
            var defaultColor = $(this).data('default-color');
            updatePreview(inputId, defaultColor);
        }
    });

    function updatePreview(inputId, color) {
        var previewBox = $('#whp-preview-box');
        var previewButton = previewBox.find('button');
        var previewLink = previewBox.find('.whp-preview-link');
        
        if (inputId.includes('[background_color]')) {
            previewBox.css('background-color', color);
        } else if (inputId.includes('[text_color]')) {
            previewBox.css('color', color);
            previewBox.find('h3').css('color', color);
        } else if (inputId.includes('[button_color]')) {
            previewButton.css('background-color', color);
        } else if (inputId.includes('[button_text_color]')) {
            previewButton.css('color', color);
        } else if (inputId.includes('[link_color]')) {
            previewLink.css('color', color);
        }
    }

    // Trigger initial preview update on page load
    $('.whp-color-picker').each(function() {
        var inputId = $(this).attr('name');
        var color = $(this).val();
        updatePreview(inputId, color);
    });
});

