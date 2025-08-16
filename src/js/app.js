function load_countries() {
    $.get('../modules/load.php', function (data) {
        $("#table_body").html(data);
    });
}
function search_contacts_old(query) { // Renamed to avoid conflict
    $.post('../modules/search.php', { search: query }, function (data) {
        $("#table_body").html(data);
    });
}
function showform(id = '', fname = '', lname = '', numbers = []) {
    $("#action").val("save");
    $('#form_title').text(id ? window.I18N['edit_contact'] : window.I18N['add_contact']);
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
            
                <input type="text" class="form-control" name="number[]" value="${num}" placeholder="${window.I18N['enter_number']}">
                <button type="button" class="btn btn-outline-danger remove-number ${i === 0 ? 'd-none' : ''}">${window.I18N['remove']}</button>
            </div>
        `);
    });

    // Clear previously selected users
    selectedUsers = [];
    $('#selected_users_display').empty();
    $('#shared_with_users_hidden').val('');


}
$(document).on('click', '#add-number', function () {
    const newField = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="number[]" placeholder="${window.I18N['enter_number']}">
            <button type="button" class="btn btn-outline-danger remove-number">${window.I18N['remove']}</button>
        </div>`;
    $('#number-fields').append(newField);
});
$(document).on('click', '.remove-number', function () {
    $(this).closest('.input-group').remove();
});
let contactsData = []; // Reintroduce contactsData to store raw data
let currentSort = { field: 'id_contact', direction: 'asc' }; // Initialize currentSort

function loadContacts(page = 1, records_per_page = 10, sort_field = currentSort.field, sort_direction = currentSort.direction) {
    $.get('../modules/load.php', { page: page, records_per_page: records_per_page, sort_field: sort_field, sort_direction: sort_direction }, function (data) {
        console.log("Response from load.php:", data);
        const response = data;
        contactsData = response.contacts; // Store raw contacts data
        renderContactsTable(contactsData); // Render contacts directly from server response
        renderPaginationControls(response.total_pages, page);
        $("#current_page").val(page);
    });
}

function renderPaginationControls(totalPages, currentPage) {
    let paginationHtml = '';
    const $paginationControls = $("#pagination_controls");
    if (totalPages <= 1) {
        $paginationControls.html('').hide();
        return;
    }
    $paginationControls.show();
    for (let i = 1; i <= totalPages; i++) {
        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    $paginationControls.html(paginationHtml);
}

function search_contacts(query) {
    const records_per_page = $("#records_per_page").val();
    const page = $("#current_page").val(); // Get current page for search to maintain it
    $.post('../modules/search.php', { search: query, records_per_page: records_per_page, page: page, sort_field: currentSort.field, sort_direction: currentSort.direction }, function (data) {
        const response = data;
        renderContactsTable(response.contacts);
        renderPaginationControls(response.total_pages, page); // Maintain current page or reset to 1 if search changes total pages
    });
}

function renderContactsTable(data) {
    let html = '';
    if (data.length === 0) {
        html = `<tr><td colspan='7' class='text-center'>${window.I18N['no_contacts_found']}</td></tr>`;
    } else {
        data.forEach((contact, i) => {
            const id = contact.id_contact;
            const fname = contact.firstname_contact;
            const lname = contact.lastname_contact;
            const numbers = contact.numbers_array ? JSON.stringify(contact.numbers_array) : '[]';

            // Avatar Logic
            let avatar_html;
            if (contact.photo_contact && contact.photo_contact !== '' && fileExists('../' + contact.photo_contact)) {
                avatar_html = `<img src="../${contact.photo_contact}" alt="Contact Image" width="50" height="50" class="img-thumbnail rounded-circle">`;
            } else {
                const char = fname ? fname.charAt(0).toUpperCase() : '';
                const colors = ['#6f42c1', '#198754', '#0d6efd', '#fd7e14', '#dc3545', '#20c997'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                avatar_html = `<div class="rounded-circle text-white text-center d-flex justify-content-center align-items-center" style="background-color:${color};width:50px;height:50px;font-size:1.2rem;font-weight:bold;">${char}</div>`;
            }

            // Social Media Icons
            let social_media_html = '';
            if (contact.numbers_array && contact.numbers_array.length > 0) {
                const first_number = contact.numbers_array[0];
                const normalized_phone = first_number.replace(/^\+98/, '').replace(/[^0-9]/g, '').replace(/^0+/, '');
                const whatsapp_link = `https://wa.me/98${normalized_phone}`;
                social_media_html = `
                    <a href="${whatsapp_link}" target="_blank" title="WhatsApp" class="text-success me-2"><i class="fab fa-whatsapp fs-4"></i></a>
                    <a href="#" title="Telegram (Not Linkable)" class="text-muted"><i class="fab fa-telegram fs-4"></i></a>
                `;
            }
            const numbers_html = contact.numbers_array ? contact.numbers_array.map(num => `<span>${num}</span>`).join('<br>') : '';
            const actions_html = `
                <a href="#" class="text-primary edit-btn" title="${window.I18N['edit']}" data-id="${id}" data-fname="${fname}" data-lname="${lname}" data-numbers='${numbers}'>
                    <i class="fa fa-edit fa-fw fs-5"></i>
                </a>
                <a href="#" class="text-danger delete-btn" title="${window.I18N['delete']}" data-id="${id}" data-fname="${fname}" data-lname="${lname}">
                    <i class="fa fa-trash fa-fw fs-5"></i>
                </a>
            `;

            html += `
                <tr>
                    <td class="align-middle">${i + 1}</td>
                    <td class="align-middle">${avatar_html}</td>
                    <td class="align-middle">${fname}</td>
                    <td class="align-middle">${lname}</td>
                    <td class="align-middle">${numbers_html}</td>
                    <td class="align-middle">${social_media_html}</td>
                    <td class="align-middle">${actions_html}</td>
                </tr>
            `;
        });
    }
    $("#table_body").html(html);
}

let selectedUsers = []; // Global array to store selected users for sharing

function searchUsers(query) {
    if (query.length < 2) {
        $('#search_results').empty();
        return;
    }
    $.get('../modules/search_users.php', { query: query }, function (data) {
        let resultsHtml = '';
        if (data.length > 0) {
            data.forEach(user => {
                // Only show users not already selected
                if (!selectedUsers.some(selected => selected.id === user.id)) {
                    resultsHtml += `<a href="#" class="list-group-item list-group-item-action search-result-item" data-id="${user.id}" data-username="${user.username}">${user.username} (${user.email})</a>`;
                }
            });
        } else {
            resultsHtml = `<div class="list-group-item">${window.I18N['no_users_found'] ?? 'No users found.'}</div>`;
        }
        $('#search_results').html(resultsHtml);
    });
}

function addSelectedUser(id, username) {
    selectedUsers.push({ id: id, username: username });
    renderSelectedUsers();
}

function removeSelectedUser(id) {
    selectedUsers = selectedUsers.filter(user => user.id !== id);
    renderSelectedUsers();
}

function renderSelectedUsers() {
    let displayHtml = '';
    let hiddenInputVal = '';
    selectedUsers.forEach(user => {
        displayHtml += `<span class="badge bg-primary text-white me-1 mb-1 p-2">${user.username} <i class="fa fa-times-circle remove-selected-user" data-id="${user.id}" style="cursor:pointer;"></i></span>`;
        hiddenInputVal += `<input type="hidden" name="shared_with_users[]" value="${user.id}">`;
    });
    $('#selected_users_display').html(displayHtml);
    // Update the hidden input that actually gets submitted with the form
    $('#shared_with_users_hidden').html(hiddenInputVal);

    // Clear search results and input after selection
    $('#search_users_input').val('');
    $('#search_results').empty();
}

function fileExists(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status != 404;
}

function sortContacts(field) {
    if (currentSort.field === field) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.field = field;
        currentSort.direction = 'asc';
    }

    // Remove all existing sort icons
    $(".sort-icon i").removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
    // Update the clicked sort icon
    const icon = currentSort.direction === 'asc' ? '<i class="fa fa-sort-up"></i>' : '<i class="fa fa-sort-down"></i>';
    // Assuming you have a way to identify the correct button to attach the icon to, e.g., a data-field attribute
    // This part might need adjustment based on your HTML structure for sort buttons
    $('button[data-field="' + field + '"]').html($('button[data-field="' + field + '"]').text() + ' ' + icon);

    const currentPage = $("#current_page").val();
    const recordsPerPage = $("#records_per_page").val();
    loadContacts(currentPage, recordsPerPage, currentSort.field, currentSort.direction);
}

$(document).ready(function () {
    $("#import_contact").hide()
    loadContacts(1, $("#records_per_page").val());
    $("#add_contacts").hide();
    $("#add").click(() => showform());
    $("#cancel").click(() => {
        $("#add_contacts").hide();
        $("#show_contacts").show();
        $("#form1")[0].reset();
        $("#form_title").text('');
        selectedUsers = []; // Clear selected users on cancel
        renderSelectedUsers(); // Update display
    });
    $("input[name='search']").on("input", function () {
        const query = $(this).val().trim();
        if (query.length > 0) {
            search_contacts(query);
        } else {
            loadContacts(1, $("#records_per_page").val());
        }
    });
    $("#form1").submit(function (e) {
        e.preventDefault();
        const action = $("#action").val();
        const url = action === "delete" ? "../modules/delete.php" : "../modules/save.php";

        // Use FormData to handle file uploads
        var formData = new FormData(this);
        // Add selected shared user IDs to formData
        selectedUsers.forEach(user => {
            formData.append('shared_with_users[]', user.id);
        });

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
                $("#message").html("<div class='alert alert-danger'>" + window.I18N['error_occurred'] + ": " + textStatus + "</div>");
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

        // Fetch and display shared users when editing
        $.get('../modules/get_shared_users.php', { contact_id: id }, function(data) {
            selectedUsers = data; // Assuming data is an array of {id, username}
            renderSelectedUsers();
        });
    });
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const fname = $(this).data('fname');
        const lname = $(this).data('lname');
        $("#id").val(id);
        $("#first_name").val(fname).prop("readonly", true);
        $("#last_name").val(lname).prop("readonly", true);
        $("#number-fields").html('<p class="text-muted">' + window.I18N['numbers_will_be_deleted'] + '</p>');
        $("#action").val("delete");
        $('#form_title').text(window.I18N['delete_contact'] + ': ' + fname + ' ' + lname);
        $("#add_contacts").show();
        $("#show_contacts").hide();
    });
    $("#import").click(function (){
        $("#import_contact").show();
        $("#show_contacts").hide();
    })

    $(document).on('click', '.sort-icon', function () {
        const field = $(this).data('field');
        if (['action', 'social_media', 'picture', 'numbers'].includes(field)) return; // Exclude non-sortable fields
        sortContacts(field);
    });

    // Handle click on the new sort buttons for UI only
    $(document).on('click', '.sort-btn', function () {
        const field = $(this).data('field');
        sortContacts(field); // Re-use existing sort logic
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const records_per_page = $("#records_per_page").val();
        loadContacts(page, records_per_page, currentSort.field, currentSort.direction);

        // Update active class for pagination buttons
        $(".page-item").removeClass('active');
        $(this).closest('.page-item').addClass('active');
    });

    // Re-fetch contacts after save/delete operation
    const originalSuccess = $.ajaxSettings.success;
    $.ajaxSetup({
        success: function(response) {
            if (originalSuccess) originalSuccess.apply(this, arguments);
            // Check if it's a save/delete response
            if (typeof response === 'string' && response.includes("alert-success")) {
                const currentPage = $("#current_page").val();
                const recordsPerPage = $("#records_per_page").val();
                loadContacts(currentPage, recordsPerPage, currentSort.field, currentSort.direction); // Reload all data with current sort
            }
        }
    });

    // User search and selection logic
    let searchTimeout;
    $('#search_users_input').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        searchTimeout = setTimeout(() => {
            searchUsers(query);
        }, 300); // Debounce for 300ms
    });

    $(document).on('click', '.search-result-item', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const username = $(this).data('username');
        addSelectedUser(id, username);
    });

    $(document).on('click', '.remove-selected-user', function() {
        const id = $(this).data('id');
        removeSelectedUser(id);
    });
});