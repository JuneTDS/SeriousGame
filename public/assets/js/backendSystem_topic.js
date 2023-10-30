$(document).ready(function() {
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    $(document).on("click", "#overlay", function(event) {
        if (event.target === document.getElementById('overlay')) {
            $(".popup-form").hide();
            $("#overlay").hide();
        }
    });
    
    $('.input-field, .dropdown').on('change', function () {
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

    $(document).on("click", "#open-popup-btn", function(e) {
        e.preventDefault();

        $("#popup-form").show();
        $("#overlay").show();
    });

    $(document).on("click", "#close", function(e) {
        $(".popup-form").hide();
        $("#overlay").hide();
    });

    $(document).on("click", "#close_reload", function(e) {
        $(".popup-form").hide();
        $("#overlay").hide();
        window.location.reload();
    });

    $(document).on("click", "#create-btn", function(e) {
        e.preventDefault();

        let topic = $("#topic").val();
        let subject = $("#subject").val();
        let hour_dropdown = $("#hour-dropdown").val();
        let minute_dropdown = $("#minute-dropdown").val();

        var formData = {
            _token: _token,
            topic: topic,
            subject: subject,
            hour_dropdown: hour_dropdown,
            minute_dropdown: minute_dropdown
        };

        var type = "POST";
        var ajaxurl = '/admin/topic/create';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
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

    $(document).on("click", "#update-btn", function(e) {
        e.preventDefault();

        let topicId = $(".update-id").val();
        let topic = $(".update-name").val();
        let hour_dropdown = $(".hour-dropdown-update").val();
        let minute_dropdown = $(".minute-dropdown-update").val();

        var formData = {
            _token: _token,
            topic: topic,
            topic_id: topicId,
            hour_dropdown: hour_dropdown,
            minute_dropdown: minute_dropdown
        };

        var type = "POST";
        var ajaxurl = '/admin/topic/update';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
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

    $(document).on("click", "#delete-btn", function(e) {
        e.preventDefault();

        let topic = $(".delete-id").val();

        var formData = {
            _token: _token,
            topic: topic
        };

        var type = "POST";
        var ajaxurl = '/admin/topic/delete';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Topic has been deleted.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
});

function showUpdateTopicPopup(topicId) {
    $("#overlay").show();
    $("#popup-form-update").show();

    let time = ($(".row-time-"+topicId).val()).split(":");
    $(`.hour-dropdown-update option[value=${time[0]}]`).attr('selected', 'selected');
    $(`.minute-dropdown-update option[value=${time[1]}]`).attr('selected', 'selected');

    $(".update-name").val($(".row-name-"+topicId).val());
    $(".update-id").val(topicId);
}

function confirmDelete(topicId) {
    $("#overlay").show();
    $("#delete-popup").show();
    $(".delete-id").val(topicId);
}

function showEditSuccessPopup() {
    $('#overlay').show();
    $('#update-success').show();

    setTimeout(function() {
        $('#update-success').hide();
        $('#overlay').hide();
    }, 2000);
}