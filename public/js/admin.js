document.querySelectorAll('.edit-cafe').forEach(button => {
    console.log('test');
    button.addEventListener('click', function () {
        const cafeId = this.getAttribute('data-id');
        fetch(`/../Cafes-Viewing-Website/app/controllers/CafeController.php?action=fetchById&id=${cafeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cafe = data.cafe;
                    document.getElementById('edit_cafe_id').value = cafe.id;
                    document.getElementById('edit_name').value = cafe.name;
                    document.getElementById('edit_description').value = cafe.description;
                    document.getElementById('edit_address').value = cafe.address;
                    document.getElementById('edit_price_range').value = cafe.price_range;
                    document.getElementById('edit_district_id').value = parseInt(cafe.district_id);
                    document.getElementById('edit_tags').value = cafe.tags.join(', ');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not load cafe data',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while loading cafe data',
                    icon: 'error'
                });
            });
    });
});

document.getElementById('editCafeForm').addEventListener('submit', function (event) {
    event.preventDefault();

    Swal.fire({
        title: 'Confirm update?',
        text: "Do you want to save the changes?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('editCafeForm');
            const saveBtn = document.querySelector('#saveEditBtn');
            const tagsInput = form.querySelector('[name="tags"]').value;
            const cafeData = {
                cafe_id: form.querySelector('[name="cafe_id"]').value,
                name: form.querySelector('[name="name"]').value,
                description: form.querySelector('[name="description"]').value,
                address: form.querySelector('[name="address"]').value,
                district_id: form.querySelector('[name="district_id"]').value,
                price_range: form.querySelector('[name="price_range"]').value,
                tags: tagsInput ? tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag !==
                    '') : []
            };

            saveBtn.disabled = true;
            saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Saving...`;
            fetch('/../Cafes-Viewing-Website/app/controllers/CafeController.php?action=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cafeData)
            })
                .then(response => {
                    console.log('Response status: ', response.status);
                    console.log('Content type: ', response.headers.get('content-type'))
                    return response.text();
                }).then(text => {
                    console.log('Raw response:', text); // Log nội dung phản hồi
                    try {
                        return JSON.parse(text); // Thử parse JSON
                    } catch (e) {
                        console.error('JSON parse error:', e, text);
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Changes';

                    document.getElementById('editFormErrors').style.display = 'none';
                    document.querySelectorAll('.edit-error-message').forEach(error => error
                        .textContent = '');

                    if (data.success) {
                        const editModal = bootstrap.Modal.getInstance(document.getElementById(
                            'editCafeModal'));
                        editModal.hide();

                        Swal.fire({
                            title: 'Success!',
                            text: 'Café updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        const errorDiv = document.getElementById('editFormErrors');
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = data.message ||
                            'An error occurred while updating the café.';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Changes';

                    const errorDiv = document.getElementById('editFormErrors');
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = 'An error occurred. Please try again.';
                });
        }
    });
});
document.querySelectorAll('.delete-cafe').forEach(button => {
    button.addEventListener('click', function () {
        const cafeId = this.getAttribute('data-id');
        const cafeName = this.getAttribute('data-name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to delete the café "${cafeName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/../Cafes-Viewing-Website/app/controllers/CafeController.php?action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cafe_id: cafeId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'The café has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Could not delete the café.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the café.',
                            'error'
                        );
                    });
            }
        });
    });
});
document.getElementById('addCafeForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const description = document.getElementById('description').value;
    if (description.length < 10 || description.length > 500) {
        const errorDiv = document.getElementById('formErrors');
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'Description must be between 10 and 500 characters.';
        return;
    }
    const formData = new FormData(this);

    fetch('/Cafes-Viewing-Website/public/admin.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        console.log('Response status: ', response.status);
        console.log('Content type: ', response.headers.get('content-type'))
        return response.text();
    }).then(text => {
        console.log('Raw response:', text); // Log nội dung phản hồi
        try {
            return JSON.parse(text); // Thử parse JSON
        } catch (e) {
            console.error('JSON parse error:', e, text);
            throw new Error('Invalid JSON response');
        }
    })
        .then(data => {
            document.getElementById('formErrors').style.display = 'none';
            document.getElementById('formErrors').innerHTML = '';
            document.querySelectorAll('.error-message').forEach(error => error.textContent = '');

            if (data.success) {
                const addModal = bootstrap.Modal.getInstance(document.getElementById('addCafeModal'));
                addModal.hide();

                Swal.fire({
                    title: 'Success!',
                    text: 'Add café successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            } else {
                if (data.errors) {
                    if (data.errors.required) {
                        const errorDiv = document.getElementById('formErrors');
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = data.errors.required;
                    } else {
                        for (const [field, error] of Object.entries(data.errors)) {
                            const errorElement = document.querySelector(
                                `.error-message[data-field="${field}"]`);
                            if (errorElement) {
                                errorElement.textContent = error;
                            }
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorDiv = document.getElementById('formErrors');
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'An error occurred. Please try again.';
        });
});

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.report-action-form');

    forms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const reportId = this.dataset.reportId;
            const action = this.dataset.action;
            const actionText = action === 'confirm' ? 'confirm this report' : 'delete this report';

            const confirmResult = await Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${actionText}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'confirm' ? '#28a745' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action}!`
            });

            if (!confirmResult.isConfirmed) return;

            const formData = new FormData();
            formData.append('report_id', reportId);
            formData.append('action', action);

            try {
                const response = await fetch('/../Cafes-Viewing-Website/app/controllers/ReportController.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                Swal.fire({
                    title: result.success ? 'Success!' : 'Error!',
                    text: result.message,
                    icon: result.success ? 'success' : 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (result.success) {
                        window.location.reload();
                    }
                });

            } catch (err) {
                console.error('Error:', err);
                Swal.fire('Oops!', 'Something went wrong. Please try again.', 'error');
            }
        });
    });
});
document.querySelectorAll('.approve-cafe').forEach(button => {
    button.addEventListener('click', function () {
        const cafeId = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to approve this café?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/Cafes-Viewing-Website/public/approve_cafe.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cafe_id: cafeId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Approved!',
                                text: 'Café has been approved.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to approve café.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    });
});

// Xử lý các hành động khác (edit, delete, report) nếu có
