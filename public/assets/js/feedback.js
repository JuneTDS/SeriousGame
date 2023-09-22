class Feedback {

    constructor() {
        this.classDropdown = $("#class");
        this.topicDropdown = $("#topic");
        this.topicTwoDropdown = $("#topic-two");

        this.feedbackSection = $(".feedback-section.topic");
        this.generalFeedbackSection = $(".feedback-section.general");
    }

    updateDropdown(data) {
        this.renderClasses(data.classes);
        this.renderTopics(data.topics);
    }

    renderClasses(classes) {
        this.classDropdown.find("option.can-remove").remove();
        classes.forEach(value => {
            this.classDropdown.append(
                `<option class="can-remove" value="${value.subject_class_id}">${value.class_name}</option>`
            )
        });
    }

    renderTopics(topics) {
        this.topicDropdown.find("option.can-remove").remove();
        this.topicTwoDropdown.find("option.can-remove").remove();
        topics.forEach(topic => {
            this.topicDropdown.append(
                `<option class="can-remove" value="${topic.topic_id}">${topic.topic_name}</option>`
            );
            this.topicTwoDropdown.append(
                `<option class="can-remove" value="${topic.topic_id}">${topic.topic_name}</option>`
            );
        });
    }

    renderFeedback(data) {
        console.log(data);
        this.feedbackSection.find(".feedback").remove();
        this.generalFeedbackSection.find(".feedback").remove();

        if (data.feedbacks.length > 0) {
            $("p.no-data.topic").hide();

            (data.feedbacks).forEach((feedback, key) => {
                let answers = (feedback.feedback.includes(",")) ? feedback.feedback.split(",") : [feedback.feedback];

                let answersEle = "";
                if (feedback.feedback == "nil") {
                    answersEle += `<label>There is currently no feedbacks</label>`;
                } else {
                    answers.forEach(value => {
                        answersEle += `<label>${value}</label>`;
                    })
                }

                this.feedbackSection.append(
                    `<div class="feedback">
                        <div class="question">
                            <label>Question ${key+1}) ${feedback.feedback_question}</label>
                        </div>
                        <div class="answer">
                            ${answersEle}
                        </div>
                    </div>`
                );
            });
            this.feedbackSection.show();
        } else {
            $("p.no-data.topic").show();
            this.feedbackSection.hide();
        }
        
        if (data.generalFeedbacks.length > 0) {
            $("p.no-data.general").hide();

            (data.generalFeedbacks).forEach((feedback, key) => {
                let answers = (feedback.feedback.includes(",")) ? feedback.feedback.split(",") : [feedback.feedback];

                let answersEle = "";
                if (feedback.feedback == "nil") {
                    answersEle += `<label>There is currently no feedbacks</label>`;
                } else {
                    answers.forEach(value => {
                        answersEle += `<label>${value}</label>`;
                    })
                }

                this.generalFeedbackSection.append(
                    `<div class="feedback">
                        <div class="question">
                            <label>Question ${key+1}) ${feedback.feedback_question}</label>
                        </div>
                        <div class="answer">
                            ${answersEle}
                        </div>
                    </div>`
                );
            });
        } else {
            $("p.no-data.general").show();
            this.generalFeedbackSection.hide();
        }
    }
}

let feedback = new Feedback();
let _token = $("input[name=_token]").val();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': _token
    }
});

$("#subject").on("change", function(e) {
    e.preventDefault();
    var formData = {
        subject: $(this).val(),
    };
    var type = "POST";
    var ajaxurl = '/user/getClassesAndTopicBySubject';
    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function (data) {
            feedback.updateDropdown(data.data);
        },
        error: function (data) {
            console.log(data);
        }
    });
});

$("#search").on("click", function(e) {
    e.preventDefault();
    findFeedback();
});

$("#topic").on("change", function(e) {
    e.preventDefault();
    findFeedback();
});

$("#topic-two").on("change", function(e) {
    e.preventDefault();
    findFeedback();
});

function findFeedback() {
    let _subject = $("#subject").val();
    let _class = $("#class").val();
    let _topic = $("#topic").val();
    let _topicTwo = $("#topic-two").val();

    var formData = {
        subject: _subject,
        class: _class,
        topic: _topic,
        topicTwo: _topicTwo,
    };
    var type = "POST";
    var ajaxurl = '/user/getFeedbacks';
    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function (data) {
            feedback.renderFeedback(data.data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}