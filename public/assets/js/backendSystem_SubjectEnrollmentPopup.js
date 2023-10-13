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
        hideCreatePopup();
        hideDeletePopup();
        document.getElementById('success-popup').style.display = 'none';
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
    var permissionName = document.getElementById('permissionName').value;
    var description = document.getElementById('description').value;
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    // Create a data object to send to the server
    var data = {
        _token: _token,
        permissionName: permissionName,
        description: description
    };
    

    // Send a POST request to the server to save the data
    $.ajax({
        url: '/admin/createPermission',
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





// Open the delete popup when the button is clicked
document.querySelectorAll('.delete-permission-btn').forEach(function(deleteButton) {
    deleteButton.addEventListener('click', function() {
        var permissionName = this.getAttribute('data-id');
        showDeletePopup(permissionName);
    });
});

// Function to show the delete popup and overlay
function showDeletePopup(permissionName) {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('delete-popup-form').style.display = 'block';

    // Store the permission name in a data attribute of the "Delete" button
    document.getElementById('delete-btn').setAttribute('data-id', permissionName);
}

// Function to hide the delete popup and overlay
function hideDeletePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('delete-popup-form').style.display = 'none';
}

// Call delete function when the "Delete" button is clicked
document.getElementById('delete-btn').addEventListener('click', function() {
    var permissionName = this.getAttribute('data-id');
    deletePermission(permissionName);
});

// Function to handle permission deletion and send data to the server
function deletePermission(permissionName) {
    let _token = $('meta[name="csrf-token"]').attr('content');
    // Send a DELETE request to the server to delete the permission
    $.ajax({
        url: '/admin/deletePermission/' + permissionName,
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




// //To switch permission between table (Start)
// document.addEventListener('DOMContentLoaded', function() {
//     const itemPermissionsTable = document.getElementById('itemPermissionsTable');
//     const permissionByRolesTable = document.getElementById('permissionByRolesTable');
//     const upBtn = document.getElementById('upBtn');
//     const downBtn = document.getElementById('downBtn');
//     let selectedRow;

//     // Function to move selected row from itemPermissionsTable to permissionByRolesTable
//     function moveRowUp() {
//         if (selectedRow && !selectedRow.parentElement.isSameNode(permissionByRolesTable)) {
//             itemPermissionsTable.querySelector('tbody').removeChild(selectedRow);
//             permissionByRolesTable.querySelector('tbody').appendChild(selectedRow);
//             selectedRow.classList.remove('selected');
//             selectedRow = null;
//         }
//     }

//     // Function to move selected row from permissionByRolesTable to itemPermissionsTable
//     function moveRowDown() {
//         if (selectedRow && !selectedRow.parentElement.isSameNode(itemPermissionsTable)) {
//             permissionByRolesTable.querySelector('tbody').removeChild(selectedRow);
//             itemPermissionsTable.querySelector('tbody').appendChild(selectedRow);
//             selectedRow.classList.remove('selected');
//             selectedRow = null;
//         }
//     }

//     // Attach click event listeners to move the rows
//     upBtn.addEventListener('click', moveRowUp);
//     downBtn.addEventListener('click', moveRowDown);

//     // Add click event listeners to rows for selecting them
//     itemPermissionsTable.querySelectorAll('tr').forEach(row => {
//         row.addEventListener('click', () => {
//             if (selectedRow) {
//                 selectedRow.classList.remove('selected');
//             }
//             row.classList.add('selected');
//             selectedRow = row;
//         });
//     });

//     permissionByRolesTable.querySelectorAll('tr').forEach(row => {
//         row.addEventListener('click', () => {
//             if (selectedRow) {
//                 selectedRow.classList.remove('selected');
//             }
//             row.classList.add('selected');
//             selectedRow = row;
//         });
//     });
// });
// //To switch permission between table (End)