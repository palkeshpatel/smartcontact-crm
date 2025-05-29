<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Contact CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .contact-card {
            transition: transform 0.2s;
        }

        .contact-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .merge-checkbox {
            transform: scale(1.2);
        }

        .custom-field {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-primary"><i class="fas fa-address-book me-2"></i>Smart Contact CRM</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-plus me-2"></i>Add Contact
                    </button>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Search by name or email...">
                            </div>
                            <div class="col-md-3">
                                <select id="genderFilter" class="form-select">
                                    <option value="">All Genders</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button id="clearFilters" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button id="mergeBtn" class="btn btn-warning" disabled>
                                    <i class="fas fa-code-merge me-1"></i>Merge Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacts List -->
                <div id="contactsList" class="row">
                    <!-- Contacts will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="pagination" class="d-flex justify-content-center mt-4">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="contactForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="male">
                                        <label class="form-check-label">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="female">
                                        <label class="form-check-label">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="other">
                                        <label class="form-check-label">Other</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Additional File</label>
                                <input type="file" name="additional_file" class="form-control">
                            </div>
                        </div>

                        <!-- Custom Fields -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Custom Fields</h6>
                                <button type="button" id="addCustomField" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>Add Field
                                </button>
                            </div>
                            <div id="customFields">
                                <!-- Custom fields will be added here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Merge Modal -->
    <div class="modal fade" id="mergeModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Merge Contacts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="mergeContent">
                        <!-- Merge content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmMerge" class="btn btn-warning">Confirm Merge</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let selectedContacts = [];

            // CSRF token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Load contacts
            function loadContacts(page = 1) {
                const search = $('#searchInput').val();
                const gender = $('#genderFilter').val();

                $.ajax({
                    url: '/contacts/data',
                    method: 'GET',
                    data: {
                        page,
                        search,
                        gender
                    },
                    success: function(response) {
                        if (response.success) {
                            renderContacts(response.data.contacts);
                            renderPagination(response.data.pagination);
                        }
                    },
                    error: function() {
                        showAlert('Error loading contacts', 'danger');
                    }
                });
            }

            // Render contacts
            function renderContacts(contacts) {
                let html = '';
                contacts.forEach(contact => {
                    const profileImg = contact.profile_image ?
                        `/storage/${contact.profile_image}` :
                        'https://via.placeholder.com/60x60?text=No+Image';

                    let customFieldsHtml = '';
                    if (contact.custom_fields && contact.custom_fields.length > 0) {
                        customFieldsHtml = contact.custom_fields.map(field =>
                            `<small class="text-muted d-block">${field.field_name}: ${field.field_value}</small>`
                        ).join('');
                    }

                    html += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card contact-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <input type="checkbox" class="merge-checkbox me-3 mt-1"
                                               value="${contact.id}" onchange="toggleMergeSelection(this)">
                                        <img src="${profileImg}" alt="Profile" class="profile-img rounded-circle me-3">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">${contact.name}</h6>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>${contact.email}
                                                </small>
                                            </p>
                                            ${contact.phone ? `<p class="card-text mb-1"><small class="text-muted"><i class="fas fa-phone me-1"></i>${contact.phone}</small></p>` : ''}
                                            ${contact.gender ? `<p class="card-text mb-1"><small class="text-muted"><i class="fas fa-user me-1"></i>${contact.gender}</small></p>` : ''}
                                            ${customFieldsHtml}
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editContact(${contact.id})">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteContact(${contact.id})">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#contactsList').html(html);
            }

            // Render pagination
            function renderPagination(pagination) {
                let html = '';
                if (pagination.last_page > 1) {
                    html = '<nav><ul class="pagination">';

                    // Previous button
                    if (pagination.current_page > 1) {
                        html +=
                            `<li class="page-item"><a class="page-link" href="#" onclick="loadContacts(${pagination.current_page - 1})">Previous</a></li>`;
                    }

                    // Page numbers
                    for (let i = 1; i <= pagination.last_page; i++) {
                        const active = i === pagination.current_page ? 'active' : '';
                        html +=
                            `<li class="page-item ${active}"><a class="page-link" href="#" onclick="loadContacts(${i})">${i}</a></li>`;
                    }

                    // Next button
                    if (pagination.current_page < pagination.last_page) {
                        html +=
                            `<li class="page-item"><a class="page-link" href="#" onclick="loadContacts(${pagination.current_page + 1})">Next</a></li>`;
                    }

                    html += '</ul></nav>';
                }
                $('#pagination').html(html);
            }

            // Toggle merge selection
            window.toggleMergeSelection = function(checkbox) {
                const contactId = parseInt(checkbox.value);
                if (checkbox.checked) {
                    if (selectedContacts.length < 2) {
                        selectedContacts.push(contactId);
                    } else {
                        checkbox.checked = false;
                        showAlert('You can only select 2 contacts for merging', 'warning');
                    }
                } else {
                    selectedContacts = selectedContacts.filter(id => id !== contactId);
                }

                $('#mergeBtn').prop('disabled', selectedContacts.length !== 2);
            };

            // Contact form submission
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const customFields = {};

                $('#customFields .custom-field').each(function() {
                    const name = $(this).find('input[name="field_name"]').val();
                    const value = $(this).find('input[name="field_value"]').val();
                    if (name && value) {
                        customFields[name] = value;
                    }
                });

                if (Object.keys(customFields).length > 0) {
                    Object.keys(customFields).forEach(key => {
                        formData.append(`custom_fields[${key}]`, customFields[key]);
                    });
                }

                const contactId = $('#contactForm').data('contact-id');
                const url = contactId ? `/contacts/${contactId}` : '/contacts';
                const method = contactId ? 'PUT' : 'POST';

                if (contactId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#contactModal').modal('hide');
                            $('#contactForm')[0].reset();
                            $('#customFields').empty();
                            loadContacts(currentPage);
                            showAlert(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            let errorMsg = '';
                            Object.values(response.errors).forEach(errors => {
                                errors.forEach(error => errorMsg += error + '<br>');
                            });
                            showAlert(errorMsg, 'danger');
                        } else {
                            showAlert(response.message || 'An error occurred', 'danger');
                        }
                    }
                });
            });

            // Add custom field
            $('#addCustomField').on('click', function() {
                const fieldHtml = `
                    <div class="custom-field">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="field_name" class="form-control" placeholder="Field Name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field_value" class="form-control" placeholder="Field Value">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="$(this).closest('.custom-field').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#customFields').append(fieldHtml);
            });

            // Edit contact
            window.editContact = function(id) {
                $.ajax({
                    url: `/contacts/${id}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const contact = response.data;

                            // Fill form fields
                            $('#contactForm')[0].reset();
                            $('#contactForm').data('contact-id', id);
                            $('.modal-title').text('Edit Contact');

                            $('input[name="name"]').val(contact.name);
                            $('input[name="email"]').val(contact.email);
                            $('input[name="phone"]').val(contact.phone);
                            if (contact.gender) {
                                $(`input[name="gender"][value="${contact.gender}"]`).prop('checked',
                                    true);
                            }

                            // Load custom fields
                            $('#customFields').empty();
                            if (contact.custom_fields) {
                                contact.custom_fields.forEach(field => {
                                    const fieldHtml = `
                                        <div class="custom-field">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="field_name" class="form-control" value="${field.field_name}">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="field_value" class="form-control" value="${field.field_value}">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="$(this).closest('.custom-field').remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    $('#customFields').append(fieldHtml);
                                });
                            }

                            $('#contactModal').modal('show');
                        }
                    }
                });
            };

            // Delete contact
            window.deleteContact = function(id) {
                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: `/contacts/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                loadContacts(currentPage);
                                showAlert(response.message, 'success');
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            showAlert(response.message || 'An error occurred', 'danger');
                        }
                    });
                }
            };

            // Merge contacts
            $('#mergeBtn').on('click', function() {
                if (selectedContacts.length !== 2) {
                    showAlert('Please select exactly 2 contacts to merge', 'warning');
                    return;
                }

                $.ajax({
                    url: '/contacts/merge/data',
                    method: 'POST',
                    data: {
                        contact_ids: selectedContacts
                    },
                    success: function(response) {
                        if (response.success) {
                            renderMergeModal(response.data);
                            $('#mergeModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        showAlert(response.message || 'An error occurred', 'danger');
                    }
                });
            });

            // Render merge modal
            function renderMergeModal(contacts) {
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Select Master Contact:</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="master_contact" value="${contacts[0].id}" checked>
                                <label class="form-check-label">
                                    <strong>${contacts[0].name}</strong><br>
                                    <small class="text-muted">${contacts[0].email}</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="master_contact" value="${contacts[1].id}">
                                <label class="form-check-label">
                                    <strong>${contacts[1].name}</strong><br>
                                    <small class="text-muted">${contacts[1].email}</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Merge Preview:</h6>
                            <p class="text-muted">The selected master contact will retain its data, and additional data from the other contact will be merged.</p>
                        </div>
                    </div>
                `;
                $('#mergeContent').html(html);
            }

            // Confirm merge
            $('#confirmMerge').on('click', function() {
                const masterContactId = $('input[name="master_contact"]:checked').val();
                const mergeContactId = selectedContacts.find(id => id != masterContactId);

                $.ajax({
                    url: '/contacts/merge',
                    method: 'POST',
                    data: {
                        master_contact_id: masterContactId,
                        merge_contact_id: mergeContactId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#mergeModal').modal('hide');
                            selectedContacts = [];
                            $('.merge-checkbox').prop('checked', false);
                            $('#mergeBtn').prop('disabled', true);
                            loadContacts(currentPage);
                            showAlert(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        showAlert(response.message || 'An error occurred', 'danger');
                    }
                });
            });

            // Search and filter
            $('#searchInput, #genderFilter').on('input change', function() {
                currentPage = 1;
                loadContacts(currentPage);
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#genderFilter').val('');
                currentPage = 1;
                loadContacts(currentPage);
            });

            // Reset modal on close
            $('#contactModal').on('hidden.bs.modal', function() {
                $('#contactForm')[0].reset();
                $('#contactForm').removeData('contact-id');
                $('.modal-title').text('Add Contact');
                $('#customFields').empty();
            });

            // Show alert
            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('body').append(alertHtml);

                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Initial load
            loadContacts();
        });
    </script>
</body>

</html>
