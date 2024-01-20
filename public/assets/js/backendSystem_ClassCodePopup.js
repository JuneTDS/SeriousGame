// class classcode{
//     updateDropdown(data) {
//         this.renderClasses(data.classes);
//         this.renderTopics(data.topics);
//     }
// }

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
        $('#overlay, #create-popup-form').show();
    }

    // Function to hide the create popup and overlay
    function hideCreatePopup() {
        $('#overlay, #create-popup-form').hide();
    }

    // Function to show the success popup and overlay
    function showSuccessPopup() {
        $('#overlay, #success-popup').show();

        // Hide the success popup and overlay after 2 seconds
        setTimeout(function() {
            $('#success-popup, #overlay').hide();
        }, 2000);
    }

    $('#createEndDate').on('change', function () {
        let startDate = $("#createStartDate").val();
        let endDate = $("#createEndDate").val();

        var date1 = new Date(startDate);
        var date2 = new Date(endDate);

        if (date1.getTime() > date2.getTime()) {
            console.log('Date1 is earlier than Date2');
            $("#create-popup-form .alert-danger").text("End date should not be lesser than start date");
            $("#create-popup-form .alert-danger").show();
            $('#create-btn').attr("disabled", true);
        } else {
            $("#create-popup-form .alert-danger").hide();
            $('#create-btn').removeAttr("disabled");
        }
    });

    $('#editEndDate').on('change', function () {
        let startDate = $("#editStartDate").val();
        let endDate = $("#editEndDate").val();

        var date1 = new Date(startDate);
        var date2 = new Date(endDate);

        if (date1.getTime() > date2.getTime()) {
            console.log('Date1 is earlier than Date2');
            $(".alert-danger").text("End date should not be lesser than start date");
            $(".alert-danger").show();
            $('#update-btn').attr("disabled", true);
        } else {
            $(".alert-danger").hide();
            $('#update-btn').removeAttr("disabled");
        }
    });

    $('#createSubject').on('change', function () {
        var subjectId = $(this).val();

        // Send an AJAX request to retrieve matching classes
        $.ajax({
            url: '/admin/getSubjectClasses/' + subjectId,
            type: 'GET',
            success: function (data) {
                // Clear the existing options in the Class dropdown
                $('#createClass').empty();

                // Populate the Class dropdown with the retrieved data
                $.each(data.data, function (key, value) {
                    $('#createClass').append('<option value="' + key + '">' + value + '</option>');
                });                
            }
        });
    });

    $('#create-btn').on('click', function() {
        // Get the form data
        var classCode = $('#createClassCode').val();
        var subject = $('#createSubject').val();
        var subject_Class = $('#createClass').val();
        var classSize = $('#createClassSize').val();
        var startDate = $('#createStartDate').val();
        var endDate = $('#createEndDate').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        
        // Create a data object to send to the server
        var data = {
            _token: _token,
            classCode: classCode,
            subject: subject,
            subject_Class: subject_Class,
            classSize: classSize,
            startDate: startDate,
            endDate: endDate
        };
        
        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/createClassCode',
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
                    $(".alert-danger.alert").text(response.message)
                    $(".alert-danger.alert").show();
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors here
                console.error(error);
            }
        });
    });

    $('#editSubject').on('change', function () {
        var subjectId = $(this).val();

        // Send an AJAX request to retrieve matching classes
        $.ajax({
            url: '/admin/getSubjectClasses/' + subjectId,
            type: 'GET',
            success: function (data) {
                // Clear the existing options in the Class dropdown
                $('#editClass').empty();

                // Populate the Class dropdown with the retrieved data
                $.each(data.data, function (key, value) {
                    $('#editClass').append('<option value="' + key + '">' + value + '</option>');
                });                
            }
        });
    });

    $('#update-btn').on('click', function() {
        // Get the form data
        var classCodeId = $('#editClassCodeId').val();
        var classCode = $('#editClassCode').val();
        var subject = $('#editSubject').val();
        var subject_Class = $('#editClass').val();
        var classSize = $('#editClassSize').val();
        var startDate = $('#editStartDate').val();
        var endDate = $('#editEndDate').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        
        // Create a data object to send to the server
        var data = {
            _token: _token,
            classCodeId: classCodeId,
            classCode: classCode,
            subject: subject,
            subject_Class: subject_Class,
            classSize: classSize,
            startDate: startDate,
            endDate: endDate
        };
        
        // Send a POST request to the server to save the data
        $.ajax({
            url: '/admin/classCodeEditSave',
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
    $('.delete-popup-btn').on('click', function() {
        var class_code_id = $(this).data('id');
        showDeletePopup(class_code_id);
    });

    // Function to show the delete popup and overlay
    function showDeletePopup(class_code_id) {
        $('#overlay, #delete-popup-form').show();
        $('#delete-btn').attr('data-id', class_code_id);
    }

    // Function to hide the delete popup and overlay
    function hideDeletePopup() {
        $('#overlay, #delete-popup-form').hide();
    }

    // Call delete function when the "Delete" button is clicked
    $('#delete-btn').on('click', function() {
        var class_code_id = $(this).data('id');
        deleteClassCode(class_code_id);
    });

    // Function to handle permission deletion and send data to the server
    function deleteClassCode(class_code_id) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        // Send a DELETE request to the server to delete the permission
        $.ajax({
            url: '/admin/deleteClassCode/' + class_code_id,
            type: 'DELETE',
            data: {
                _token: _token,
            },
            success: function(response) {
                if (response.success) {
                    hideDeletePopup();
                    window.location.href = "/admin/classCodesDashboard";
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
});
