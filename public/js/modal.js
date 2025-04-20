function showReportForm(id, type) {
        document.getElementById(`reportOptions${id}`).style.display = 'none';
        document.getElementById(`reportForm${id}`).style.display = 'block';

        const reportButton = document.querySelector(`[data-bs-target="#reportModal${id}"]`);
        console.log(reportButton.innerHTML);
        console.log(reportButton.getAttribute('data-cafe-id'));
        console.log(reportButton.getAttribute('data-cafe-name'));
        let currentValue = '';
        switch (type) {
            case 'name':
                currentValue = reportButton.getAttribute('data-cafe-name');
                console.log(currentValue);
                document.getElementById(`currentValue${id}`).value = currentValue;
                break;
            case 'address':
                currentValue = reportButton.getAttribute('data-cafe-address');
                document.getElementById(`currentValue${id}`).value = currentValue;
                break;
            case 'price_range':
                currentValue = reportButton.getAttribute('data-cafe-price');
                document.getElementById(`currentValue${id}`).value = currentValue;
                break;
            case 'other':
                document.getElementById(`currentValue${id}`).value = 'Other information';
                break;
        }
        document.getElementById(`reportType${id}`).value = type;
    }

    function hideReportForm(id) {
        document.getElementById(`reportForm${id}`).style.display = 'none';
        document.getElementById(`reportOptions${id}`).style.display = 'block';
    }
    function submitReport(id) {
    const currentValue = document.getElementById(`currentValue${id}`).value;
    const proposedValue = document.getElementById(`proposedValue${id}`).value;
    const reportType = document.getElementById(`reportType${id}`).value;
    const reportData = {
        cafeId: id,
        reportType: reportType,
        currentValue: currentValue,
        proposedValue: proposedValue,
        timestamp: new Date().toISOString()
    };
    console.log(reportData);
    
    Swal.fire({
        title: 'Submitting Report',
        text: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('/../mywebsite/app/controllers/ReportController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(reportData)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Report submitted:', data);
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Report has been sent to admin for review!',
                confirmButtonColor: '#3085d6'
            });
            
            hideReportForm(id);
            let reportModal = document.getElementById(`reportModal${id}`);
            if (reportModal) {
                let modalInstance = bootstrap.Modal.getInstance(reportModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        })
        .catch(error => {
            console.log('Error:', error);
            
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while sending the report, please try again!',
                confirmButtonColor: '#d33'
            });
        });
}
function togglePin(cafeId) {
    console.log(cafeId);
    const pinBtn = document.querySelector(`#cafeModal${cafeId} .pin-btn`);
    fetch('/../mywebsite/app/controllers/SaveController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=save&cafe_id=${cafeId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.pinned) {
                pinBtn.classList.remove('btn-outline-primary');
                pinBtn.classList.add('btn-primary');
                pinBtn.innerHTML = '<i class="bi bi-pin-angle-fill"></i> Saved';
                alert(data.message);
            } else {
                pinBtn.classList.remove('btn-primary');
                pinBtn.classList.add('btn-outline-primary');
                pinBtn.innerHTML = '<i class="bi bi-pin-angle"></i> Save';
                alert(data.message);
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while pinning the cafe');
    });
}

document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;
    if (modal.id.startsWith('cafeModal')) {
        const cafeId = modal.id.replace('cafeModal', '');
        fetch('/../mywebsite/app/controllers/SaveController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=check&cafe_id=${cafeId}`
        })
        .then(response => response.json())
        .then(data => {
            const pinBtn = modal.querySelector('.pin-btn');
            if (data.pinned) {
                pinBtn.classList.remove('btn-outline-primary');
                pinBtn.classList.add('btn-primary');
                pinBtn.innerHTML = '<i class="bi bi-pin-angle-fill"></i> Saved';
            } else {
                pinBtn.classList.remove('btn-primary');
                pinBtn.classList.add('btn-outline-primary');
                pinBtn.innerHTML = '<i class="bi bi-pin-angle"></i> Save';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

