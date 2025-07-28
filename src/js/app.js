
function load_countries() {
    $.get('../modules/load.php', function (data) {
        $("#table_body").html(data);
    });
}
function search_contacts(query) {
    $.post('../modules/search.php', { search: query }, function (data) {
        $("#table_body").html(data);
    });
}
function showform(id = '', fname = '', lname = '', numbers = []) {
    $("#action").val("save");
    $('#form_title').text(id ? "Edit Contact" : "Add Contact");
    $("#id").val(id);
    $("#first_name").val(fname).prop("readonly", false);
    $("#last_name").val(lname).prop("readonly", false);
    $("#add_contacts").show();
    $("#show_contacts").hide();
    $('#number-fields').html('');
    if (numbers.length === 0) numbers.push('');
    numbers.forEach((num, i) => {
        $('#number-fields').append(`
            <div class="input-group mb-2">
            
                <input type="text" class="form-control" name="number[]" value="${num}" placeholder="Enter number">
                <button type="button" class="btn btn-outline-danger remove-number ${i === 0 ? 'd-none' : ''}">Remove</button>
            </div>
        `);
    });
}
$(document).on('click', '#add-number', function () {
    const newField = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="number[]" placeholder="Enter number">
            <button type="button" class="btn btn-outline-danger remove-number">Remove</button>
        </div>`;
    $('#number-fields').append(newField);
});
$(document).on('click', '.remove-number', function () {
    $(this).closest('.input-group').remove();
});
$(document).ready(function () {
    $("#import_contact").hide()
    load_countries();
    $("#add_contacts").hide();
    $("#add").click(() => showform());
    $("#cancel").click(() => {
        $("#add_contacts").hide();
        $("#show_contacts").show();
        $("#form1")[0].reset();
        $("#form_title").text('');
    });
    $("input[name='search']").on("input", function () {
        const query = $(this).val().trim();
        if (query.length > 0) {
            search_contacts(query);
        } else {
            load_countries();
        }
    });
    $("#form1").submit(function (e) {
        e.preventDefault();
        const action = $("#action").val();
        const url = action === "delete" ? "../modules/delete.php" : "../modules/save.php";

        // Use FormData to handle file uploads
        var formData = new FormData(this);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#message").html(response).addClass("animate__animated animate__fadeIn");
                if (response.includes("alert-success")) {
                    setTimeout(() => {
                        $("#message").removeClass("animate__fadeIn").addClass("animate__fadeOut");
                        setTimeout(() => {
                            $("#message").empty().removeClass();
                            location.reload();
                        }, 1000);
                    }, 2000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#message").html("<div class='alert alert-danger'>An error occurred: " + textStatus + "</div>");
            }
        });
    });
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const fname = $(this).data('fname');
        const lname = $(this).data('lname');
        const numbers = JSON.parse($(this).attr('data-numbers'));
        showform(id, fname, lname, numbers);
        $("#action").val("save");
    });
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const fname = $(this).data('fname');
        const lname = $(this).data('lname');
        $("#id").val(id);
        $("#first_name").val(fname).prop("readonly", true);
        $("#last_name").val(lname).prop("readonly", true);
        $("#number-fields").html('<p class="text-muted">Numbers will be deleted with contact</p>');
        $("#action").val("delete");
        $('#form_title').text(`Delete Contact: ${fname} ${lname}`);
        $("#add_contacts").show();
        $("#show_contacts").hide();
    });
    $("#import").click(function (){
        $("#import_contact").show();
        $("#show_contacts").hide();
    })
});