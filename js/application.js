var opts = {
    lines: 13 // The number of lines to draw
    , length: 28 // The length of each line
    , width: 14 // The line thickness
    , radius: 42 // The radius of the inner circle
    , scale: 0.25 // Scales overall size of the spinner
    , corners: 1 // Corner roundness (0..1)
    , color: '#000' // #rgb or #rrggbb or array of colors
    , opacity: 0.25 // Opacity of the lines
    , rotate: 0 // The rotation offset
    , direction: 1 // 1: clockwise, -1: counterclockwise
    , speed: 1 // Rounds per second
    , trail: 70 // Afterglow percentage
    , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
    , zIndex: 2e9 // The z-index (defaults to 2000000000)
    , className: 'spinner' // The CSS class to assign to the spinner
    , top: '50%' // Top position relative to parent
    , left: '50%' // Left position relative to parent
    , shadow: false // Whether to render a shadow
    , hwaccel: false // Whether to use hardware acceleration
    , position: 'absolute' // Element positioning
};

$(document).ready(function () {
    
    $.cookieBar({
        message: 'Nasza strona internetowa używa plików cookies (tzw. ciasteczka) w celach funkcjonalnych. Każdy może zaakceptować pliki cookies albo ma możliwość wyłączenia ich w przeglądarce, ale niektóre funkcjonalności strony nie będa dostępne.',
        acceptText: 'Rozumiem'
    });

    setTimeout(function () {
        $('#infoMessage').fadeOut();
    }, 3000);

    $('#loginForm').ajaxForm({
        url: 'auth/login',
        success: function (responseText, statusText, xhr, $form) {

            if (responseText == 'user' || responseText == 'admin') {
                location.href = responseText;
            } else {

                $('#loginAlert').html(responseText);
                $('#loginAlert').fadeIn();

                setTimeout(function () {
                    $('#loginAlert').fadeOut();
                }, 3000);

            }
        }
    });

    $(".chosen").chosen({
        disable_search_threshold: 10
    });

    $('#dataTable').DataTable({
        "language": {
            "url": "./js/Polish.json"
        }
    });

    $('#dataTableNoOrdering').DataTable({
        "language": {
            "url": "./js/Polish.json"
        },
        "ordering": false
    });

    $('.status').change(function () {

        if ($(this).prop('checked')) {
            $.ajax({
                type: "POST",
                url: "auth/activate/" + $(this).attr("id"),
                success: function (data) {
                    $('#infoMessage').html(data);
                    $('#infoMessage').fadeIn();

                    setTimeout(function () {
                        $('#infoMessage').fadeOut();
                    }, 3000);
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "auth/deactivate/" + $(this).attr("id"),
                success: function (data) {
                    $('#infoMessage').html(data);
                    $('#infoMessage').fadeIn();

                    setTimeout(function () {
                        $('#infoMessage').fadeOut();
                    }, 3000);
                }
            });
        }

    });

    $('#random_questions').change(function () {

        $.ajax({
            type: "POST",
            url: "tests/random_questions/" + $(this).attr("data-testid"),
            success: function (data) {
                $('#set_order_button').attr('disabled', !$('.toggle').hasClass('off'));
            }
        });

    });

    $('#question_category_select').change(function () {
        location.href = "questions_base/questions/" + $('#question_category_select').val();
    });

    $('#generate_code').click(function () {
        var hash = CryptoJS.MD5(Date());
        $('#test_access_code').val(hash.toString().substring(0, 8));
    });

    $('#addQuestions').on('shown.bs.modal', function (e) {
        var target = document.getElementById('questions_list')
        var spinner = new Spinner(opts).spin(target);
        $.ajax({
            type: "POST",
            url: "tests/get_questions/" + $('#questions_category').attr('data-testid') + "/" + $('#questions_category').val(),
            success: function (data) {
                $('#questions_list').html(data);
            }
        });
    });

    $('#questionsOrder').on('shown.bs.modal', function (e) {
        var target = document.getElementById('questions_list_order')
        var spinner = new Spinner(opts).spin(target);
        $.ajax({
            type: "POST",
            url: "tests/get_test_questions/" + $('#questions_list_order').attr('data-testid'),
            success: function (data) {
                $('#questions_list_order').html(data);
                $("#questions_list_order").sortable({
                    opacity: 0.6,
                    cursor: 'move'
                });
            }
        });
    });

    $('#questions_category').change(function () {
        var target = document.getElementById('questions_list')
        var spinner = new Spinner(opts).spin(target);
        $.ajax({
            type: "POST",
            url: "tests/get_questions/" + $('#questions_category').attr('data-testid') + "/" + $('#questions_category').val(),
            success: function (data) {
                $('#questions_list').html(data);
            }
        });
    });

});

var idToDelete = 0;

function confirmDelete(id) {
    idToDelete = id;
    $('#confirmDelete').modal();
}

function confirmRemove() {
    $('#confirmRemove').modal();
}

function deleteUser() {

    $.ajax({
        type: "POST",
        url: "auth/delete_user/" + idToDelete,
        success: function (data) {
            location.href = 'auth';
        },
//        error: function (data) {
//            //location.href = 'auth';
//            //alert(data);
//        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function deleteGroup() {

    $.ajax({
        type: "POST",
        url: "auth/delete_group/" + idToDelete,
        success: function (data) {
            location.href = 'auth/groups';
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function deleteCategory() {

    $.ajax({
        type: "POST",
        url: "Questions_base/delete_category/" + idToDelete,
        success: function (data) {
            location.href = 'questions_base/categories';
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function deleteQuestion() {

    $.ajax({
        type: "POST",
        url: "Questions_base/delete_question/" + idToDelete,
        success: function (data) {
            location.href = 'questions_base/questions';
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function deleteQuestionFromTest(id) {

    $.ajax({
        type: "POST",
        url: "Tests/delete_question/" + idToDelete + "/" + id,
        success: function (data) {
            location.href = "tests/test/" + id;
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function removeQuestionsFromTest(id) {

    $.ajax({
        type: "POST",
        url: "Tests/delete_questions/" + id,
        success: function (data) {
            location.href = "tests/test/" + id;
        }
    });

    $('#confirmRemove').modal('hide');
}

function deleteTest() {

    $.ajax({
        type: "POST",
        url: "Tests/delete_test/" + idToDelete,
        success: function (data) {
            location.href = 'tests';
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function deleteTestAttempt(id) {

    $.ajax({
        type: "POST",
        url: "Tests/delete_test_attempt/" + idToDelete,
        success: function (data) {
            location.href = 'tests/test_attempts/'+id;
        }
    });

    idToDelete = 0;
    $('#confirmDelete').modal('hide');
}

function addQuestion() {
    $('#addQuestion').modal();
    $('#typeRadio0').radio();
}

function addQuestions() {
    $('#addQuestions').modal();

}

function changeQuestionOrder() {
    $('#questionsOrder').modal();
}

function saveOrder(id) {
    var order = $("#questions_list_order").sortable("serialize") + '&testid=' + id;
    $.post("Tests/save_order", order, function (theResponse) {
        //$("#organizuj_album").dialog("close");
        //aktualizujZdjecia(id_albumu_oa.val());
        location.href = 'tests/test/' + id;
    });
}

function toggleChecked(status) {
    $("#questions_list .checkbox").each(function () {
        if (!status) {
            $(this).checkbox('check');
        } else {
            $(this).checkbox('uncheck');
        }
    });
}

function confirmEndTest(){
    $('#confirmEnd').modal();
}

function endTest(id){
//    $.ajax({
//        type: "POST",
//        url: "Test/end_test/" + id,
//        success: function (data) {
//            location.href = '';
//        }
//    });
}