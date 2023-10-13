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
        let subtopic = $("#subtopic").val();
        let url = $("#url").val();
        let easy = $("#easy").val();
        let difficult = $("#difficult").val();
        let score = $("#score").val();

        var formData = {
            _token: _token,
            topic: topic,
            subtopic: subtopic,
            url: url,
            easy: easy,
            difficult: difficult,
            score: score
        };

        var type = "POST";
        var ajaxurl = '/admin/subtopic/create';
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

        let subtopicId = $(".update-id").val();
        let subtopic = $("#update_subtopic").val();
        let url = $("#update_url").val();
        let easy = $("#update_easy").val();
        let difficult = $("#update_difficult").val();
        let score = $("#update_score").val();

        var formData = {
            _token: _token,
            subtopicId: subtopicId,
            subtopic: subtopic,
            url: url,
            easy: easy,
            difficult: difficult,
            score: score
        };

        var type = "POST";
        var ajaxurl = '/admin/subtopic/update';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Changes have been saved successfully.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $(document).on("click", "#delete-btn", function(e) {
        e.preventDefault();

        let subtopic = $(".delete-id").val();

        var formData = {
            _token: _token,
            subtopic: subtopic
        };

        var type = "POST";
        var ajaxurl = '/admin/subtopic/delete';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Subtopic has been deleted.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
});

function showUpdateSubtopicPopup(subtopicId) {
    $("#overlay").show();
    $("#popup-form-update").show();

    $("#update_subtopic").val($(".row-name-"+subtopicId).val());
    $("#update_url").val($(".row-url-"+subtopicId).val());
    $("#update_easy").val($(".row-easy-"+subtopicId).val());
    $("#update_difficult").val($(".row-difficult-"+subtopicId).val());
    $("#update_score").val($(".row-score-"+subtopicId).val());
    $(".update-id").val(subtopicId);
}

function confirmDelete(topicId) {
    $("#overlay").show();
    $("#delete-popup").show();
    $(".delete-id").val(topicId);
}