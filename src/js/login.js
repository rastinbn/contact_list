
function loginUser(formData) {
    $.ajax({
        url: "../../modules/login/LoginUser.php",
        method: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.success) {
     
                showAlert('success', response.message);
                
          
                setTimeout(() => {
                    window.location.href = '../index.php';
                }, 1500);
            } else {
        
                showAlert('danger', response.message);
            }
 
            $("#submit").prop('disabled', false).text('Login');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            
            let errorMessage = 'An error occurred. Please try again.';
     
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
            
                if (xhr.responseText) {
                    errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 100);
                }
            }
            
            showAlert('danger', errorMessage);
            
        
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
    
    
    $(".alert").remove();
    
  
    $(".card-body").prepend(alertHtml);
    

    setTimeout(() => {
        $(".alert").fadeOut();
    }, 5000);
}

function validateForm() {
    const username = $("[name='username']").val().trim();
    const password = $("[name='password']").val();
    

    if (!username) {
        showAlert('danger', window.I18N['username_required']);
        $("[name='username']").focus();
        return false;
    }
    
    if (!password) {
        showAlert('danger', window.I18N['password_required']);
        $("[name='password']").focus();
        return false;
    }
    
    return true;
}

$(() => {

    $("#submit").click(function(e) {
        e.preventDefault();
        
        if (validateForm()) {

            $("#submit").prop('disabled', true).text('Logging in...');
            
            const formData = $("#LoginForm").serialize();
            loginUser(formData);
        }
    });
    

    $("#LoginForm").on('keypress', function(e) {
        if (e.which === 13) {
            $("#submit").click();
        }
    });

    $("[name='username']").on("blur", function() {
        const username = $(this).val().trim();
        if (username && username.length < 2) {
            showAlert('danger', window.I18N['username_min_length']);
        }
    });
    

    $("input").on("input", function() {
        $(".alert").fadeOut();
    });
    

    $("[name='username']").focus();
}); 