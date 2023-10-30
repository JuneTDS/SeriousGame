$(document).ready(function() {
    // Close the popups when clicking outside
    $('#overlay').on('click', function(event) {
        if (event.target === $('#overlay')[0]) {
            hideCreatePopup();
            hideDeletePopup();
            hideEditPopup();
            $('#success-popup').hide();
            $('#update-success-popup').hide();
        }
    });

    // Open the create popup when the button is clicked
    $('#create-popup-btn').on('click', showCreatePopup);

    function showCreatePopup() {
        $('#overlay').show();
        $('#create-popup-form').show();
    }

    function hideCreatePopup() {
        $('#overlay').hide();
        $('#create-popup-form').hide();
    }

    function showSuccessPopup() {
        $('#overlay').show();
        $('#success-popup').show();
        
        setTimeout(function() {
            $('#success-popup').hide();
            $('#overlay').hide();
        }, 2000);
    }

    $('#create-btn').on('click', function() {
        var createClassName = $('#createClassName').val();
        var createAcademicYear = $('#createAcademicYear').val();
        var createAcademicSemester = $('#createAcademicSemester').val();
        var createLecturerId = $('#createLecturerId').val();
        var createSubjectId = $('#createSubjectId').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        
        var data = {
            _token: _token,
            createClassName: createClassName,
            createAcademicYear: createAcademicYear,
            createAcademicSemester: createAcademicSemester,
            createLecturerId: createLecturerId,
            createSubjectId: createSubjectId
        };
        
        $.ajax({
            url: '/admin/createLectureClass',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    hideCreatePopup();
                    showSuccessPopup();
                    location.reload();
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Open the edit popup when the button is clicked
    $('.edit-popup-btn').on('click', function() {
        var classData = {
            subjectClassId: $(this).data('subject-class-id'),
            className: $(this).data('class-name'),
            academicYear: $(this).data('academic-year'),
            academicSemester: $(this).data('academic-semester'),
            lecturerId: $(this).data('lecturer-id'),
            subjectId: $(this).data('subject-id')
        };

        populateEditPopup(classData);

        showEditPopup();
    });

    // Function to populate the edit popup with lecture class details
    function populateEditPopup(classData) {
        $('#subjectClass_Update').val(classData.subjectClassId);
        $('#class_Update').val(classData.className);
        $('#year_Update').val(classData.academicYear);
        $('#sem_Update').val(classData.academicSemester);
        $('#lecturer_Update').val(classData.lecturerId);
        $('#subject_Update').val(classData.subjectId);
    }

    function showEditPopup() {
        $('#overlay').show();
        $('#edit-popup-form').show();
    }

    function hideEditPopup() {
        $('#overlay').hide();
        $('#edit-popup-form').hide();
    }

    function showEditSuccessPopup() {
        $('#overlay').show();
        $('#update-success-popup').show();

        setTimeout(function() {
            $('#update-success-popup').hide();
            $('#overlay').hide();
        }, 2000);
    }

    $('#update-btn').on('click', function() {
        var subjectClass_Update = $('#subjectClass_Update').val();
        var class_Update = $('#class_Update').val();
        var year_Update = $('#year_Update').val();
        var sem_Update = $('#sem_Update').val();
        var lecturer_Update = $('#lecturer_Update').val();
        var subject_Update = $('#subject_Update').val();
        var _token = $('meta[name="csrf-token"]').attr('content');

        var data = {
            _token: _token,
            subjectClass_Update: subjectClass_Update,
            class_Update: class_Update,
            year_Update: year_Update,
            sem_Update: sem_Update,
            lecturer_Update: lecturer_Update,
            subject_Update: subject_Update
        };

        $.ajax({
            url: '/admin/lectureClassEditSave',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    hideEditPopup();
                    showEditSuccessPopup();
                    location.reload();
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Open the delete popup when the button is clicked
    $('.delete-popup-btn').on('click', function() {
        var lectureClass = $(this).data('id');
        showDeletePopup(lectureClass);
    });

    function showDeletePopup(lectureClass) {
        $('#overlay').show();
        $('#delete-popup-form').show();
        $('#delete-btn').attr('data-id', lectureClass);
    }

    function hideDeletePopup() {
        $('#overlay').hide();
        $('#delete-popup-form').hide();
    }

    $('#delete-btn').on('click', function() {
        var lectureClass = $('#delete-btn').data('id');
        deleteLectureClass(lectureClass);
    });

    function deleteLectureClass(lectureClass) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/admin/deleteLectureClass/' + lectureClass,
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
});