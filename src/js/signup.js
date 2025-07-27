function createUser(formData) {
    $.ajax({
        url: "../../modules/signup/CreatUser.php",
        method: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Show success message
                showAlert('success', response.message + ' Redirecting to login page...');
                
                // Redirect to login page after 2 seconds
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
            } else {
                // Show error message
                showAlert('danger', response.message);
            }
            
            // Re-enable submit button
            $("#submit").prop('disabled', false).text('Create Account');
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
            $("#submit").prop('disabled', false).text('Create Account');
        }
    });
}

function updateBarColor(width) {
    if (width > 75) {
        $(".bar").css("background-color", "darkgreen");
        $(".password-status").html("<small class='mt-1' style='color: darkgreen'>Your password is very strong</small>");
    } else if (width > 50) {
        $(".bar").css("background-color", "green");
        $(".password-status").html("<small class='mt-1 text-success'>Your password is strong</small>");
    } else if (width > 25) {
        $(".bar").css("background-color", "yellow");
        $(".password-status").html("<small class='mt-1' style='color: #856404'>Your password is medium</small>");
    } else if (width > 0) {
        $(".bar").css("background-color", "red");
        $(".password-status").html("<small class='text-danger mt-1'>Your password is weak</small>");
    } else {
        $(".bar").css("background-color", "transparent");
        $(".password-status").html("");
    }
}

function animateBarWidth(newWidth) {
    $(".bar").animate({
        width: newWidth + "%"
    }, {
        duration: 600,
        easing: "swing"
    });
}

function addPulseEffect() {
    $(".bar").addClass("pulse-animation");
    setTimeout(() => {
        $(".bar").removeClass("pulse-animation");
    }, 600);
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
    
    // Auto-dismiss after 8 seconds for errors, 5 seconds for success
    const dismissTime = type === 'danger' ? 8000 : 5000;
    setTimeout(() => {
        $(".alert").fadeOut();
    }, dismissTime);
}
function validateForm() {
    const username = $("[name='username']").val().trim();
    const email = $("[name='email']").val().trim();
    const password = $("[name='password']").val();
    const confirmPassword = $("[name='ConfirmPassword']").val();
    // Basic validation
    if (!username) {
        showAlert('danger', 'Username is required');
        return false;
    }
    
    if (!email) {
        showAlert('danger', 'Email is required');
        return false;
    }
    
    if (!password) {
        showAlert('danger', 'Password is required');
        return false;
    }
    
    if (!confirmPassword) {
        showAlert('danger', 'Please confirm your password');
        return false;
    }
    
    if (password !== confirmPassword) {
        showAlert('danger', 'Passwords do not match');
        return false;
    }
    
    return true;
}

$(() => {
    // Add CSS styles for animations
    $("<style>")
        .prop("type", "text/css")
        .html(`
            .bar {
                transition: background-color 0.4s ease-in-out;
                border-radius: 3px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            }
            .pulse-animation {
                animation: pulse 0.6s ease-in-out;
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }
        `)
        .appendTo("head");

    let initialWidth = parseInt($(".bar").attr("data-width")) || 0;
    updateBarColor(initialWidth);
    
    let password = $("[name='password']");
    let confirmPassword = $("[name='ConfirmPassword']");
    var width = 0;
    var previousWidth = 0;

    // Password strength checker
    $(password).on("blur", function() {
        const passwordVal = password.val();
        const confirmPasswordVal = confirmPassword.val();
        
        if (passwordVal) {
            $.post("../../modules/signup/CheckPassword.php", {
                password: passwordVal,
                ConfirmPassword: confirmPasswordVal
            }, function(response) {
                if (response.success) {
                    previousWidth = width;
                    width = response.width;
                    
                    // Animate width change
                    animateBarWidth(width);
                    $(".bar").attr("data-width", `${width}`);
                    
                    // Update color with delay to match animation
                    setTimeout(() => {
                        updateBarColor(width);
                        
                        // Add pulse effect if significant change
                        if (Math.abs(width - previousWidth) > 20) {
                            addPulseEffect();
                        }
                    }, 300);
                    
                    // Show password match status
                    if (passwordVal && confirmPasswordVal) {
                        if (response.passwordsMatch) {
                            $(".password-match").html("<small class='text-success'>Passwords match</small>");
                        } else {
                            $(".password-match").html("<small class='text-danger'>Passwords do not match</small>");
                        }
                    }
                }
            }, "json").fail(function(xhr, status, error) {
                console.error('Password check failed:', error);
            });
        }
    });
    
    // Confirm password checker
    $(confirmPassword).on("blur", function() {
        const passwordVal = password.val();
        const confirmPasswordVal = confirmPassword.val();
        
        if (passwordVal && confirmPasswordVal) {
            if (passwordVal === confirmPasswordVal) {
                $(".password-match").html("<small class='text-success'>Passwords match</small>");
            } else {
                $(".password-match").html("<small class='text-danger'>Passwords do not match</small>");
            }
        }
    });
    
    $(password).on("focus", function() {
        previousWidth = width;
        width = 0;
        // Animate to zero
        animateBarWidth(0);
        $(".bar").attr("data-width", "0");
        
        setTimeout(() => {
            updateBarColor(0);
        }, 300);
    });

    // Form submission
    $("#submit").click(function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Disable submit button to prevent double submission
            $("#submit").prop('disabled', true).text('Creating Account...');
            
            const formData = $("#SignUpForm").serialize();
            createUser(formData);
        }
    });
    
    // Real-time username validation
    $("[name='username']").on("blur", function() {
        const username = $(this).val().trim();
        if (username && username.length < 3) {
            showAlert('danger', 'Username must be at least 3 characters long');
        }
    });
    
    // Real-time email validation
    $("[name='email']").on("blur", function() {
        const email = $(this).val().trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showAlert('danger', 'Please enter a valid email address');
        }
    });
});