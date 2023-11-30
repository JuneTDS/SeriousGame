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

        let subtopic = $("#subtopic").val();
        let difficulty = $("#difficulty").val();
        let type = $("#type").val();
        let name = $("#name").val();
        let mcq_a = $("#mcq_a").val();
        let mcq_b = $("#mcq_b").val();
        let mcq_c = $("#mcq_c").val();
        let mcq_d = $("#mcq_d").val();
        let answer = $("#answer").val();
        let hint = $("#hint").val();
        let score = $("#score").val();

        var formData = {
            _token: _token,
            difficulty: difficulty,
            subtopic: subtopic,
            type: type,
            name: name,
            mcq_a: mcq_a,
            mcq_b: mcq_b,
            mcq_c: mcq_c,
            mcq_d: mcq_d,
            answer: answer,
            hint: hint,
            score: score
        };

        var ajaxurl = '/admin/question/create';
        $.ajax({
            type: "POST",
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

        let difficulty = $(`#update_difficulty`).val();
        let type = $(`#update_type`).val();
        let name = $("#update_name").val();
        let mcq_a = $("#update_mcq_a").val();
        let mcq_b = $("#update_mcq_b").val();
        let mcq_c = $("#update_mcq_c").val();
        let mcq_d = $("#update_mcq_d").val();
        let answer = $("#update_answer").val();
        let hint = $("#update_hint").val();
        let score = $("#update_score").val();
        let questionId = $(".update-id").val();

        var formData = {
            _token: _token,
            difficulty: difficulty,
            type: type,
            name: name,
            mcq_a: mcq_a,
            mcq_b: mcq_b,
            mcq_c: mcq_c,
            mcq_d: mcq_d,
            answer: answer,
            hint: hint,
            score: score,
            questionId: questionId
        };

        var ajaxurl = '/admin/question/update';
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Changes have been saved successfully.");
                    $("#success-popup").show();
                    showEditSuccessPopup();
                    // location.reload();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $(document).on("click", "#delete-btn", function(e) {
        e.preventDefault();

        let question = $(".delete-id").val();

        var formData = {
            _token: _token,
            question: question
        };

        var type = "POST";
        var ajaxurl = '/admin/question/delete';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.data) {
                    $(".popup-form").hide();
                    $("#success-popup .message").text("Question has been deleted.");
                    $("#success-popup").show();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    //Javascript to control the MCQ field in create and update form (Start)
    // Add a change event listener to the "update_type" select element
    $('#type').change(function() {
        checkCreateSelectedOption();
    });

    $('#update_type').change(function() {
        checkUpdateSelectedOption();
    });
    //Javascript to control the MCQ field in create and update form (End)
});

function checkCreateSelectedOption() {
    var selectedOption = $('#type').val();
    if (selectedOption === 'mcq') {
        // If "MCQ" is selected, show the MCQ input fields
        $('#create_mcq_a_field').show();
        $('#create_mcq_b_field').show();
        $('#create_mcq_c_field').show();
        $('#create_mcq_d_field').show();
    } else {
        // If "Short Answered Questions" or other options are selected, hide the MCQ input fields
        $('#create_mcq_a_field').hide();
        $('#create_mcq_b_field').hide();
        $('#create_mcq_c_field').hide();
        $('#create_mcq_d_field').hide();
    }
}

function checkUpdateSelectedOption() {
    var selectedOption = $('#update_type').val();
    if (selectedOption === 'mcq') {
        // If "MCQ" is selected, show the MCQ input fields
        $('#update_mcq_a_field').show();
        $('#update_mcq_b_field').show();
        $('#update_mcq_c_field').show();
        $('#update_mcq_d_field').show();
    } else {
        // If "Short Answered Questions" or other options are selected, hide the MCQ input fields
        $('#update_mcq_a_field').hide();
        $('#update_mcq_b_field').hide();
        $('#update_mcq_c_field').hide();
        $('#update_mcq_d_field').hide();
    }
}

function showUpdateSubtopicPopup(questionId) {
    $("#overlay").show();
    $("#popup-form-update").show();

    $(`#update_difficulty option[value=${$(".row-difficulty-"+questionId).val()}]`).attr('selected', 'selected');
    $(`#update_type option[value=${$(".row-type-"+questionId).val()}]`).attr('selected', 'selected');
    $("#update_name").val($(".row-name-"+questionId).val());
    $("#update_mcq_a").val($(".row-mcq-a-"+questionId).val());
    $("#update_mcq_b").val($(".row-mcq-b-"+questionId).val());
    $("#update_mcq_c").val($(".row-mcq-c-"+questionId).val());
    $("#update_mcq_d").val($(".row-mcq-d-"+questionId).val());
    $("#update_answer").val($(".row-answer-"+questionId).val());
    $("#update_hint").val($(".row-hint-"+questionId).val());
    $("#update_score").val($(".row-score-"+questionId).val());

    $(".update-id").val(questionId);
}

function confirmDelete(questionId) {
    $("#overlay").show();
    $("#delete-popup").show();
    $(".delete-id").val(questionId);
}

function showEditSuccessPopup() {
    $('#overlay').show();
    $('#update-success').show();

    // setTimeout(function() {
    //     $('#update-success').hide();
    //     $('#overlay').hide();
    // }, 2000);
}