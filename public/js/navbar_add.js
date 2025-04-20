document.getElementById('addCafeForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const description = document.getElementById('description').value;
    if (description.length < 10 || description.length > 500) {
        const errorDiv = document.getElementById('formErrors');
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'Description must be between 10 and 500 characters.';
        return;
    }
    const formData = new FormData(this);

    fetch('/mywebsite/public/userAdd.php', {
            method: 'POST',
            body: formData
        }).then(response => {
                        console.log('Response status: ', response.status);
                        console.log('Content type: ', response.headers.get('content-type'));
                        return response.text();
                    }).then(text => {
        console.log('Raw response:', text);
        try {
            return JSON.parse(text);
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
                    text: 'Café submitted for admin approval!',
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
