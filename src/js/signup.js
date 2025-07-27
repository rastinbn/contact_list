function CreatUSer(query){
    $.ajax({
        url:"../../modules/signup/CreatUser.php",
        method:"post",
        data:query,
        success : function (response){
            console.log(response)
        },

    })
}

function updateBarColor(width) {
    if (width > 75) {
        $(".bar").css("background-color", "darkgreen");
        $(".password-status").html("<small class=' mt-1 ' style='color: darkgreen'>your password is too strong</small>")

    } else if (width > 50) {
        $(".bar").css("background-color", "green");
        $(".password-status").html("<small class=' mt-1 text-success '>your password is strong</small>")

    } else if (width > 25) {
        $(".bar").css("background-color", "yellow");
        $(".password-status").html("<small class=' mt-1 ' style='color: black'>your password is normal</small>")
    } else if (width > 0) {
        $(".bar").css("background-color", "red");
        $(".password-status").html("<small class='text-danger mt-1 '>your password is too weak</small>")
    } else {
        $(".bar").css("background-color", "transparent");

    }
}

// Function to animate bar width
function animateBarWidth(newWidth) {
    $(".bar").animate({
        width: newWidth + "%"
    }, {
        duration: 600,
        easing: "swing"
    });
}

// Function to add pulse effect
function addPulseEffect() {
    $(".bar").addClass("pulse-animation");
    setTimeout(() => {
        $(".bar").removeClass("pulse-animation");
    }, 600);
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
    
    let password = $("[name = 'password']");
    let ConfirmPassword = $("[name = 'ConfirmPassword']");
    var width = 0;
    var previousWidth = 0;

    $(password).on("blur", function (){
        console.log("ok")
        $.post("../../modules/signup/CheckPassword.php", {
            password: password.val(), 
            ConfirmPassword: ConfirmPassword.val()
        }, function (res){
            previousWidth = width;
            width = parseInt(res) || 0;
            
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
        });
    });
    
    $(password).on("focus", function (){
        previousWidth = width;
        width = 0;
        // Animate to zero
        animateBarWidth(0);
        $(".bar").attr("data-width", "0");
        
        // Reset color
        setTimeout(() => {
            updateBarColor(0);
        }, 300);
    });

    $("#submit").click(function (){
        CreatUSer($("#SignUpForm").serialize());
    });
});