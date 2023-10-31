$(document).ready(function() {
    // Close the popups when clicking outside
    $('#overlay').on('click', function(event) {
        if (event.target === $('#overlay')[0]) {
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

    // Function to show the success popup and overlay
    function showSuccessPopup() {
        $('#overlay').show();
        $('#success-popup').show();

        // Hide the success popup and overlay after 2 seconds
        setTimeout(function() {
            $('#success-popup').hide();
            $('#overlay').hide();
        }, 2000);
    }

    $('#create-btn').on('click', function() {
        // Get the form data
        var permissionName = $('#permissionName').val();
        var description = $('#description').val();
        var _token = $('meta[name="csrf-token"]').attr('content');

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
                    location.reload();
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

    function getPermissionByRolesTableValues() {
        var permissionValues = [];
        permissionByRolesTable.find('tbody tr').each(function() {
            var permissionValue = $(this).data('permission-name');
            permissionValues.push(permissionValue);
        });
        return permissionValues;
    }

    $('#update-btn').on('click', function() {

        var permissionName = $(this).data('permission-name');

        var _token = $('meta[name="csrf-token"]').attr('content');
        var permission = $('#permissionName').val();
        var description = $('#description').val();

        var permissionsArray = getPermissionByRolesTableValues();
        var permissionsArray = ['managerRbac']; //For testing

        var data = {
            _token: _token,
            permission: permission,
            description: description,
            permissionsArray: permissionsArray
        };
        
        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/permissionEditSave/' + permissionName,
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    showSuccessPopup();
                    location.reload();
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
    $('.delete-permission-btn').on('click', function() {
        var permissionName = $(this).data('id');
        showDeletePopup(permissionName);
    });

    // Function to show the delete popup and overlay
    function showDeletePopup(permissionName) {
        $('#overlay').show();
        $('#delete-popup-form').show();
        // Store the permission name in a data attribute of the "Delete" button
        $('#delete-btn').data('id', permissionName);
    }

    // Function to handle permission deletion and send data to the server
    $('#delete-btn').on('click', function() {
        var permissionName = $(this).data('id');
        deletePermission(permissionName);
    });

    function deletePermission(permissionName) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/admin/deletePermission/' + permissionName,
            type: 'DELETE',
            data: {
                _token: _token,
            },
            success: function(response) {
                if (response.success) {
                    hideDeletePopup();
                    location.reload();
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    };

    $('#cancel-btn').on('click', function() {
        hideDeletePopup();
    });

    // Function to hide the delete popup and overlay
    function hideDeletePopup() {
        $('#overlay').hide();
        $('#delete-popup-form').hide();
    }

    // Switch permission between table (Start)
    var itemPermissionsTable = $('#itemPermissionsTable');
    var permissionByRolesTable = $('#permissionByRolesTable');
    var upBtn = $('#upBtn');
    var downBtn = $('#downBtn');
    var selectedRow;

    //To control theaction of selected permission
    // function moveRowUp() {
    //     if (selectedRow && !selectedRow.parent().is(permissionByRolesTable)) {
    //         permissionByRolesTable.find('tbody').append(selectedRow);
    //         selectedRow.removeClass('selected');
    //         selectedRow = null;
    //     }
    // }
    function moveRowUp() {
        if (selectedRow && !selectedRow.parent().is(permissionByRolesTable)) {
            // Read the data-permission-name attribute
            var permissionName = selectedRow.data('permission-name');
    
            // Append the row to the permissionByRolesTable
            permissionByRolesTable.find('tbody').append(selectedRow);
    
            // Store the data-permission-name attribute in the moved row
            selectedRow.data('permission-name', permissionName);
    
            // Remove the 'selected' class
            selectedRow.removeClass('selected');
            selectedRow = null;
        }
    }
    
    // function moveRowDown() {
    //     if (selectedRow && !selectedRow.parent().is(itemPermissionsTable)) {
    //         itemPermissionsTable.find('tbody').append(selectedRow);
    //         selectedRow.removeClass('selected');
    //         selectedRow = null;
    //     }
    // }
    function moveRowDown() {
        if (selectedRow && !selectedRow.parent().is(itemPermissionsTable)) {
            // Read the data-permission-name attribute
            var permissionName = selectedRow.data('permission-name');
    
            // Append the row to the itemPermissionsTable
            itemPermissionsTable.find('tbody').append(selectedRow);
    
            // Store the data-permission-name attribute in the moved row
            selectedRow.data('permission-name', permissionName);
    
            // Remove the 'selected' class
            selectedRow.removeClass('selected');
            selectedRow = null;
        }
    }    

    upBtn.on('click', moveRowUp);
    downBtn.on('click', moveRowDown);

    //To control the selected class
    itemPermissionsTable.on('click', 'tr', function() {
        if (selectedRow) {
            selectedRow.removeClass('selected');
        }
        $(this).addClass('selected');
        selectedRow = $(this);
    });

    permissionByRolesTable.on('click', 'tr', function() {
        if (selectedRow) {
            selectedRow.removeClass('selected');
        }
        $(this).addClass('selected');
        selectedRow = $(this);
    });
    // Switch permission between table (End)
});