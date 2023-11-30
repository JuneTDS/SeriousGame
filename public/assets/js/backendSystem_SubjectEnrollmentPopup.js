$(document).ready(function() {
    let _token = $('meta[name="csrf-token"]').attr('content');
    
    $('.input-field, .dropdown').on('change', function () {
        $('form#filter-form').submit();
    });

    $(document).on("click", "#overlay", function(event) {
        if (event.target === document.getElementById('overlay')) {
            $(".popup-form").hide();
            $("#overlay").hide();
        }
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

    $(document).on("click", "#create-popup-btn", function(e) {
        e.preventDefault();

        $("#create-popup-form").show();
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

        let subjectId = $("#subject").val();
        let userId = $("#user").val();

        var formData = {
            _token: _token,
            subjectId: subjectId,
            userId: userId
        };

        var ajaxurl = '/admin/enrollment/create';
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $("#create-popup-form").hide();
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

        let enrollId = $(".delete-id").val();

        var formData = {
            _token: _token,
            enrollId: enrollId
        };

        var type = "POST";
        var ajaxurl = '/admin/enrollment/delete';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup p").text("Enrolment has been removed.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
});

function confirmDelete(enrollId) {
    $("#overlay").show();
    $("#delete-popup-form").show();
    $(".delete-id").val(enrollId);
}