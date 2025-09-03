jQuery(document).ready(function($) {
    const disallowedDomains = [
        'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'aol.com',
        'icloud.com', 'live.com', 'msn.com', 'protonmail.com', 'zoho.com',
        'yandex.com', 'mail.com', 'gmx.com',
        'example.com', 'test.com', 'invalid.com'
    ];

    const form = $('#whp-email-form');

    if (form.length) {
        form.on('submit', function(e) {
            e.preventDefault();

            const emailInput = $('#whp_work_email');
            const workEmail = emailInput.val().trim();
            const agreeCheckbox = $('#whp_agree');
            const resultDiv = $('#whp-result');

            resultDiv.hide().html(''); // Clear previous results

            // 1. Check if the agreement checkbox is checked
            if (!agreeCheckbox.is(':checked')) {
                alert('You must agree to the terms to continue.');
                return;
            }
            
            // 2. Basic email format validation
            if (!workEmail || !/^\S+@\S+\.\S+$/.test(workEmail)) {
                alert('Please enter a valid email address.');
                emailInput.focus();
                return;
            }

            // 3. Check for disallowed domains
            const emailDomain = workEmail.substring(workEmail.lastIndexOf('@') + 1);
            if (disallowedDomains.includes(emailDomain.toLowerCase())) {
                alert('Please use a valid work email address. Free email providers are not allowed.');
                emailInput.focus();
                return;
            }

            const downloadBox = $('#whp-download-box');
            const loader = $('#whp-loader');
            const formContainer = $('#whp-form-container');
            const downloadUrl = downloadBox.data('download-url');

            formContainer.hide();
            loader.show();

            // AJAX call to save the lead
            $.ajax({
                url: whp_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'whp_save_lead',
                    email: workEmail,
                    post_id: whp_ajax_object.post_id,
                    nonce: whp_ajax_object.nonce
                },
                success: function(response) {
                    loader.hide();
                    if (response.success) {
                        const successMessage = '<p>Thank you! Your download is ready.</p>';
                        const downloadButton = `<a href="${downloadUrl}" class="whp-download-btn" download>Download the Guide</a>`;
                        resultDiv.html(successMessage + downloadButton).show();
                    } else {
                        const errorMessage = '<p class="whp-error-message">An error occurred. Please try again later.</p>';
                        resultDiv.html(errorMessage).show();
                        formContainer.show(); // Show form again on error
                    }
                },
                error: function() {
                    loader.hide();
                    const errorMessage = '<p class="whp-error-message">A server error occurred. Please try again later.</p>';
                    resultDiv.html(errorMessage).show();
                    formContainer.show(); // Show form again on error
                }
            });
        });
    }
});

