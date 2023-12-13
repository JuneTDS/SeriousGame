$(document).ready(function() {
    // Close the popups when clicking outside
    $('#overlay').on('click', function(event) {
        if (event.target === document.getElementById('overlay')) {
            hideCreatePopup();
            hideDeletePopup();
            $('#success-popup').hide();
        }
    });

    // Open the create popup when the button is clicked
    $('#create-popup-btn').on('click', showCreatePopup);

    // Function to show the create popup and overlay
    function showCreatePopup() {
        $('#overlay').show();
        $('#create-popup-form').show();
    }

    // Function to hide the create popup and overlay
    function hideCreatePopup() {
        $('#overlay').hide();
        $('#create-popup-form').hide();
    }

    function hideSuccessPopup() {
        $('#overlay').hide();
        $('#success-popup').hide();
    }

    // Function to show the success popup and overlay
    function showSuccessPopup() {
        $('#overlay').show();
        $('#success-popup').show();

        // Hide the success popup and overlay after 2 seconds
        // setTimeout(function() {
        //     $('#success-popup').hide();
        //     $('#overlay').hide();
        // }, 2000);
    }

    $('#create-btn').on('click', function() {
        // Get the form data
        var username = $('#username').val();
        var description = $('#description').val();
        var _token = $('meta[name="csrf-token"]').attr('content');

        // Create a data object to send to the server
        var data = {
            _token: _token,
            username: username,
            description: description
        };

        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/createAssignRight',
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

    $('#update-btn').on('click', function() {
        // Get the form data
        var userId = $('#userId').val();
        var roleDescription = $('#roleDescription').val();
        var _token = $('meta[name="csrf-token"]').attr('content');

        // Create a data object to send to the server
        var data = {
            _token: _token,
            userId: userId,
            roleDescription: roleDescription
        };

        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/accessRightEditSave',
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
    $('.delete-popup-btn').on('click', function() {
        var userId = $(this).data('id');
        showDeletePopup(userId);
    });

    // Function to show the delete popup and overlay
    function showDeletePopup(userId) {
        $('#overlay').show();
        $('#delete-popup-form').show();

        // Store the user ID in a data attribute of the "Delete" button
        $('#delete-btn').data('id', userId);
    }

    // Function to hide the delete popup and overlay
    function hideDeletePopup() {
        $('#overlay').hide();
        $('#delete-popup-form').hide();
    }

    // Call delete function when the "Delete" button is clicked
    $('#delete-btn').on('click', function() {
        var userId = $(this).data('id');
        deleteAssignRight(userId);
    });

    $('#close').on('click', function() {
        hideSuccessPopup();
    });

    // Function to handle permission deletion and send data to the server
    function deleteAssignRight(userId) {
        var _token = $('meta[name="csrf-token"]').attr('content');

        // Send a DELETE request to the server to delete the permission
        $.ajax({
            url: '/admin/deleteAssignRight/' + userId,
            type: 'POST',
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
    $('#cancel-btn').on('click', function() {
        hideDeletePopup();
    });

    // //To switch permission between table (Start)
    // This part of your code wasn't converted to jQuery, so I left it as is.
});