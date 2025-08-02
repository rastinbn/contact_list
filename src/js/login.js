function loginUser(formData) {
    $.ajax({
        url: "../../../modules/login/LoginUser.php",
        method: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Show success message
                showAlert('success', response.message);
                
                // Redirect to main page after 1.5 seconds
                setTimeout(() => {
                    window.location.href = '../index.php';
                }, 1500);
            } else {
                // Show error message
                showAlert('danger', response.message);
            }
            
            // Re-enable submit button
            $("#submit").prop('disabled', false).text('Login');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            
            let errorMessage = 'An error occurred. Please try again.';
            
            // Try to parse error response
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                // If not JSON, show the raw response
                if (xhr.responseText) {
                    errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 100);
                }
            }
            
            showAlert('danger', errorMessage);
            
            // Re-enable submit button
            $("#submit").prop('disabled', false).text('Login');
        }
    });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove any existing alerts
    $(".alert").remove();
    
    // Add new alert
    $(".card-body").prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $(".alert").fadeOut();
    }, 5000);
}

function validateForm() {
    const username = $("[name='username']").val().trim();
    const password = $("[name='password']").val();
    
    // Basic validation
    if (!username) {
        showAlert('danger', 'Username or email is required');
        $("[name='username']").focus();
        return false;
    }
    
    if (!password) {
        showAlert('danger', 'Password is required');
        $("[name='password']").focus();
        return false;
    }
    
    return true;
}

$(() => {
    // Form submission
    $("#submit").click(function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Disable submit button
            $("#submit").prop('disabled', true).text('Logging in...');
            
            const formData = $("#LoginForm").serialize();
            loginUser(formData);
        }
    });
    
    // Enter key submission
    $("#LoginForm").on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $("#submit").click();
        }
    });
    
    // Real-time validation
    $("[name='username']").on("blur", function() {
        const username = $(this).val().trim();
        if (username && username.length < 2) {
            showAlert('danger', 'Username or email must be at least 2 characters long');
        }
    });
    
    // Clear alerts when user starts typing
    $("input").on("input", function() {
        $(".alert").fadeOut();
    });
    
    // Focus on username field when page loads
    $("[name='username']").focus();
}); 