// Open the create user popup when the button is clicked
document.getElementById('create-popup-btn').addEventListener('click', showCreateUserPopup);

// Close the create user popup and show success popup when the "Create" button is clicked
document.getElementById('create-btn').addEventListener('click', function() {
    createUser();
});

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
function createUser() {
    // Get the form data
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var status = document.getElementById('status').value;

    // Create a data object to send to the server
    var data = {
        username: username,
        email: email,
        password: password,
        status: status
    };

    // Send a POST request to the server to save the data
    $.post('/admin/createUser', data, function(response) {
        if (response.success) { //Data Saved successfully
            hideCreateUserPopup();
            showSuccessPopup();
        } else {
            // Handle errors or display error messages
            console.error(response.message);
        }
    });
}



// Close the delete user popup when the "Delete" button is clicked
document.getElementById('delete-btn').addEventListener('click', function() {
    hideDeleteUserPopup();
});

// Close the delete user popup when the "Cancel" button is clicked
document.getElementById('cancel-btn').addEventListener('click', function() {
    hideDeleteUserPopup();
});

// Open the delete user popup when the button is clicked
document.getElementById('delete-popup-btn').addEventListener('click', showDeleteUserPopup);

// Function to show the delete user popup and overlay
function showDeleteUserPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('delete-popup-form').style.display = 'block';
}

// Function to hide the delete user popup and overlay
function hideDeleteUserPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('delete-popup-form').style.display = 'none';
}



// Close the popups when clicking outside
document.getElementById('overlay').addEventListener('click', function(event) {
    if (event.target === document.getElementById('overlay')) {
        hideCreateUserPopup();
        hideDeleteUserPopup();
        document.getElementById('success-popup').style.display = 'none';
    }
});