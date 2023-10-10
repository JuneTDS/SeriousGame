// Close the popups when clicking outside
document.getElementById('overlay').addEventListener('click', function(event) {
    if (event.target === document.getElementById('overlay')) {
        hideCreatePopup();
        hideDeletePopup();
        hideEditPopup();
        document.getElementById('success-popup').style.display = 'none';
        document.getElementById('update-success-popup').style.display = 'none';
    }
});





// Open the create popup when the button is clicked
document.getElementById('create-popup-btn').addEventListener('click', showCreatePopup);

// Function to show the create popup and overlay
function showCreatePopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('create-popup-form').style.display = 'block';
}

// Function to hide the create popup and overlay
function hideCreatePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('create-popup-form').style.display = 'none';
}

// Function to show the success popup and overlay
function showSuccessPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('success-popup').style.display = 'block';

    // Hide the success popup and overlay after 2 seconds
    setTimeout(function() {
        document.getElementById('success-popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }, 2000);
}

document.getElementById('create-btn').addEventListener('click', function() {
    // Get the form data
    var className = document.getElementById('className').value;
    var academicYear = document.getElementById('academicYear').value;
    var academicSemester = document.getElementById('academicSemester').value;
    var lecturerId = document.getElementById('lecturerId').value;
    var subjectId = document.getElementById('subjectId').value;
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    // Create a data object to send to the server
    var data = {
        _token: _token,
        className: className,
        academicYear: academicYear,
        academicSemester: academicSemester,
        lecturerId: lecturerId,
        subjectId: subjectId
    };
    

    // Send a POST request to the server to save the data
    $.ajax({
        url: '/admin/createLectureClass',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                hideCreatePopup();
                showSuccessPopup();
            } else {
                // Handle errors or display error messages
                console.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors here
            console.error(error);
        }
    });
});




// Open the edit popup when the button is clicked
document.getElementById('edit-popup-btn').addEventListener('click', showEditPopup);

// Function to show the create popup and overlay
function showEditPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('edit-popup-form').style.display = 'block';
}

// Function to hide the create popup and overlay
function hideEditPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('edit-popup-form').style.display = 'none';
}

// Function to show the success popup and overlay
function showEditSuccessPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('update-success-popup').style.display = 'block';

    // Hide the success popup and overlay after 2 seconds
    setTimeout(function() {
        document.getElementById('update-success-popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }, 2000);
}

document.getElementById('update-btn').addEventListener('click', function() {
    // Get the form data
    var userId = document.getElementById('userId').value;
    var username = document.getElementById('username').value;
    var firstName = document.getElementById('firstName').value;
    var lastName = document.getElementById('lastName').value;
    var email = document.getElementById('email').value;
    var emailGravatar = document.getElementById('emailGravatar').value;
    var password = document.getElementById('password').value;
    var status = document.getElementById('status').value;
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    // Create a data object to send to the server
    var data = {
        _token: _token,
        userId: userId,
        username: username,
        firstName: firstName,
        lastName: lastName,
        email: email,
        emailGravatar: emailGravatar,
        password: password,
        status: status
    };
    

    // Send a POST request to the server to save the data
    $.ajax({
        url: '/admin/userEditSave',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                showEditSuccessPopup();
            } else {
                // Handle errors or display error messages
                console.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors here
            console.error(error);
        }
    });
});





// Open the delete popup when the button is clicked
document.querySelectorAll('.delete-popup-btn').forEach(function(deleteButton) {
    deleteButton.addEventListener('click', function() {
        var lectureClass = this.getAttribute('data-id');
        showDeletePopup(lectureClass);
    });
});

// Function to show the delete popup and overlay
function showDeletePopup(lectureClass) {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('delete-popup-form').style.display = 'block';

    // Store the permission name in a data attribute of the "Delete" button
    document.getElementById('delete-btn').setAttribute('data-id', lectureClass);
}

// Function to hide the delete popup and overlay
function hideDeletePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('delete-popup-form').style.display = 'none';
}

// Call delete function when the "Delete" button is clicked
document.getElementById('delete-btn').addEventListener('click', function() {
    var lectureClass = this.getAttribute('data-id');
    deleteLectureClass(lectureClass);
});

// Function to handle permission deletion and send data to the server
function deleteLectureClass(lectureClass) {
    let _token = $('meta[name="csrf-token"]').attr('content');
    // Send a DELETE request to the server to delete the permission
    $.ajax({
        url: '/admin/deleteLectureClass/' + lectureClass,
        type: 'DELETE',
        data: {
            _token: _token,
        },
        success: function(response) {
            if (response.success) {
                hideDeletePopup();
                window.location.reload();
                // Optionally, you can refresh the page or update the permission list here
            } else {
                // Handle errors or display error messages
                console.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors here
            console.error(error);
        }
    });
}

// Close the delete user popup when the "Cancel" button is clicked
document.getElementById('cancel-btn').addEventListener('click', function() {
    hideDeletePopup();
});