$(document).ready(function() {
    // Get the CSRF token
    // var _token = $("input[name=_token]").val();

    // // Set up the CSRF token in AJAX headers
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': _token
    //     }
    // });

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

    $(document).on("click", "#close", function() {
        closePopup();
    })

    function closePopup() {
        window.location.href = "/admin/roleEdit/"+$("#role_name").val();
    }

    $('#create-btn').on('click', function() {
        // Get the form data
        var roleName = $('#roleName').val();
        var description = $('#description').val();
        var _token = $('meta[name="csrf-token"]').attr('content');

        // Create a data object to send to the server
        var data = {
            _token: _token,
            roleName: roleName,
            description: description
        };

        // Send a POST request to t he server to save the data
        $.ajax({
            url: '/admin/createRole',
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

    //Still fail to get the value in the Permissions by role table
    function getPermissionByRolesTableValues() {
        var permissionValues = [];
        permissionByRolesTable.find('tbody tr').each(function() {
            var permissionValue = $(this).data('permission-name');
            permissionValues.push(permissionValue);
        });
        return permissionValues;
    }

    function updateRole(roleName) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        var permissionsArray = getPermissionByRolesTableValues();

        // // Select all <tr> elements under permissionByRolesTable and loop through them
        // $('#permissionByRolesTable tbody tr').each(function() {
        //     var latest_Permission = $(this).data('permission-name');
        //     permissionsArray.push(latest_Permission);
        // });

        // var permissionsArray = ['managerRbac']; //For testing

        // Create a data object to send to the server
        var data = {
            _token: _token,
            roleName: $("#role_name").val(),
            roleDescription: $("#role_description").val(),
            permissionsArray: permissionsArray
        };
        
        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/roleEditSave/' + roleName,
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
    };

    $('#update-btn').on('click', function() {
        var roleName = $(this).data('role-name');
        updateRole(roleName);
    });

    // Open the delete popup when the button is clicked
    $('.delete-role-btn').on('click', function() {
        var roleName = $(this).data('id');
        showDeletePopup(roleName);
    });

    // Function to show the delete popup and overlay
    function showDeletePopup(roleName) {
        $('#overlay').show();
        $('#delete-popup-form').show();

        // Store the role name in a data attribute of the "Delete" button
        $('#delete-btn').data('id', roleName);
    }

    // Function to hide the delete popup and overlay
    function hideDeletePopup() {
        $('#overlay').hide();
        $('#delete-popup-form').hide();
    }

    // Call delete function when the "Delete" button is clicked
    $('#delete-btn').on('click', function() {
        var roleName = $(this).data('id');
        deleteRole(roleName);
    });

    // Function to handle role deletion and send data to the server
    function deleteRole(roleName) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        // Send a DELETE request to the server to delete the role
        $.ajax({
            url: '/admin/deleteRole/' + roleName,
            type: 'DELETE',
            data: {
                _token: _token,
            },
            success: function(response) {
                if (response.success) {
                    hideDeletePopup();
                    // window.location.reload();
                    window.location.href = '/admin/rbac_RolesDashboard';
                    // Optionally, you can refresh the page or update the role list here
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