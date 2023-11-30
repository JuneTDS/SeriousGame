$(document).ready(function() {

    let _token = $('meta[name="csrf-token"]').attr('content');

    $('.status-toggle').on('click', function(e) {
        e.preventDefault();
        var subject = $(this).data('id');   //Get the value of 'user-id' under 'status-toggle' class
        let thisToggle = $(this);
        // Send a POST request to update the user's status without expecting a response
        // $.post('/admin/usersDashboardStatus/' + userId, { }, function() {});
        var formData = {
            _token: _token,
            subject: subject,
        };
        var type = "POST";
        var ajaxurl = '/admin/subject/status';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    if (thisToggle.find("span.notPublished").length > 0) {
                        thisToggle.html(`<span class="Published">Published</span>`);
                    } else
                    if (thisToggle.find("span.Published").length > 0) {
                        thisToggle.html(`<span class="notPublished">Not Published</span>`);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    // Javascript to handle status change (End)

    // Javascript for subjectInfo page published button (Start)
    $('#change_status').on('click', function(e) {
        e.preventDefault();
        var subject = $(this).data('id');   //Get the value of 'user-id' under 'status-toggle' class
        // Send a POST request to update the user's status without expecting a response
        var formData = {
            _token: _token,
            subject: subject,
        };
        var type = "POST";
        var ajaxurl = '/admin/subject/status';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.success) {
                    if (data.data) {
                        $('#change_status').text("Unpublish");
                        $("#success-popup p.message").text("Subject has been published.")
                    } else {
                        $('#change_status').text("Publish")
                        $("#success-popup p.message").text("Subject has been unpublished.")
                    }

                    $('#overlay').show();
                    $('#success-popup').show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    // Javascript for subjectInfo page published button (End)

    // Javascript to call function immediately when filter change (Start)
    $('.input-field, .dropdown').on('change', function () {
        $('form#filter-form').submit();
    });

    $("#open-popup-btn").on("click", function() {
        $("#popup-form").show();
        $("#overlay").show();
    });

    $("#close").on("click", function() {
        $(".popup-form").hide();
        $("#overlay").hide();
    });

    
    $("#close_reload").on("click", function() {
        $(".popup-form").hide();
        $("#overlay").hide();
        window.location.reload();
    });

    $("#create-btn").on("click", function(e) {
        e.preventDefault();
        var formData = {
            _token: _token,
            subject: $("#subject").val(),
        };
        var type = "POST";
        var ajaxurl = '/admin/create/subject';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.data) {
                    $("#popup-form").hide();
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    
    $("#update-btn").on("click", function(e) {
        e.preventDefault();
        var formData = {
            _token: _token,
            subject: $(".update-id").val(),
            name: $(".update-name").val(),
        };
        var type = "POST";
        var ajaxurl = '/admin/update/subject';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.data) {
                    $("#popup-form-update").hide();
                    // $("#success-popup .message").text("Changes have been saved successfully.");
                    // $("#success-popup").show();
                    showEditSuccessPopup();
                    location.reload();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $("#delete-btn").on("click", function(e) {
        e.preventDefault();
        var formData = {
            _token: _token,
            subject: $(".delete-id").val()
        };
        var type = "POST";
        var ajaxurl = '/admin/delete/subject';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Subject has been deleted.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('.dropdown').on('change', function () {
        $('form#filter-form').submit();
    });

    // $('.input-field').on('keydown', function () {
    //     $('form#filter-form').submit();
    // });

    $('.input-field').on('keyup', function (e) {
        if (e.key == "Enter") {
            $('form#filter-form').submit();
        }
    });

    $('.input-field').on('change', function () {
        $('form#filter-form').submit();
    });

    $(document).on("click", ".sortable", function(e) {
        e.preventDefault();
        if ($(this).attr("data-column") == "name") {
            if($("#name_sort").val() == "asc") {
                $("#name_sort").val("desc");
            } else {
                $("#name_sort").val("asc");
            }
        }

        $('form#filter-form').submit();
    });

    $('#overlay').on('click', function(event) {
        if (event.target === $('#overlay')[0]) {
            // hideCreatePopup();
            // hideDeletePopup();
            // hideEditPopup();
            // $('#success-popup').hide();
            // $('#update-success-popup').hide();
            $(".popup-form").hide();
            $("#overlay").hide();
        }
    });
});

function showUpdateSubjectPopup(subjectId) {
    $("#overlay").show();
    $("#popup-form-update").show();
    $(".update-name").val($("#subject-id-"+subjectId).val());
    $(".update-id").val(subjectId);
}

function confirmDelete(subjectId) {
    $("#overlay").show();
    $("#delete-popup").show();
    $(".delete-id").val(subjectId);
}

function showEditSuccessPopup() {
    $('#overlay').show();
    $('#update-success').show();

    setTimeout(function() {
        $('#update-success').hide();
        $('#overlay').hide();
    }, 2000);
}