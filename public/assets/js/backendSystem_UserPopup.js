// Get the CSRF token
// var _token = $("input[name=_token]").val();

// // Set up the CSRF token in AJAX headers
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': _token
//     }
// });

// Close the popups when clicking outside
document.getElementById('overlay').addEventListener('click', function(event) {
    if (event.target === document.getElementById('overlay')) {
        hideCreateUserPopup();
        hideDeleteUserPopup();
        document.getElementById('success-popup').style.display = 'none';
    }
});





// Open the create user popup when the button is clicked
document.getElementById('create-popup-btn').addEventListener('click', showCreateUserPopup);

// Function to show the create user popup and overlay
function showCreateUserPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('create-popup-form').style.display = 'block';
}

// Function to hide the create user popup and overlay
function hideCreateUserPopup() {
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

// Function to handle form submission and send data to the server
// function createUser() {
//     // Get the form data
//     var username = document.getElementById('username').value;
//     var email = document.getElementById('email').value;
//     var password = document.getElementById('password').value;
//     var status = document.getElementById('status').value;

//     // Create a data object to send to the server
//     var data = {
//         username: username,
//         email: email,
//         password: password,
//         status: status
//     };

//     // Send a POST request to the server to save the data
//     $.post('/admin/createUser', data, function(response) {
//         if (response.success) { //Data Saved successfully
//             hideCreateUserPopup();
//             showSuccessPopup();
//         } else {
//             // Handle errors or display error messages
//             console.error(response.message);
//         }
//     });
// }

document.getElementById('create-btn').addEventListener('click', function() {
    // Get the form data
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var status = document.getElementById('status').value;
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    // Create a data object to send to the server
    var data = {
        _token: _token,
        username: username,
        email: email,
        password: password,
        status: status
    };
    

    // Send a POST request to the server to save the data
    $.ajax({
        url: '/admin/createUser',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                hideCreateUserPopup();
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





// Open the delete user popup when the button is clicked
document.querySelectorAll('.delete-user-btn').forEach(function(deleteButton) {
    deleteButton.addEventListener('click', function() {
        var userId = this.getAttribute('data-user-id');
        showDeleteUserPopup(userId);
    });
});

// Function to show the delete user popup and overlay
function showDeleteUserPopup(userId) {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('delete-popup-form').style.display = 'block';

    // Store the user ID in a data attribute of the "Delete" button
    document.getElementById('delete-btn').setAttribute('data-user-id', userId);
}

// Function to hide the delete user popup and overlay
function hideDeleteUserPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('delete-popup-form').style.display = 'none';
}

// Close the delete user popup when the "Delete" button is clicked
document.getElementById('delete-btn').addEventListener('click', function() {
    var userId = this.getAttribute('data-user-id');
    deleteUser(userId);
});

// Function to handle user deletion and send data to the server
function deleteUser(userId) {
    let _token = $('meta[name="csrf-token"]').attr('content');
    // Send a DELETE request to the server to delete the user
    $.ajax({
        url: '/admin/deleteUser/' + userId,
        type: 'DELETE',
        data: {
            _token: _token,
        },
        success: function(response) {
            if (response.success) {
                hideDeleteUserPopup();
                window.location.reload();
                // Optionally, you can refresh the page or update the user list here
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
    hideDeleteUserPopup();
});