//* WORKING
$('#taskEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');

    let modal = $(this);
    
    modal.find('#taskEditId').val(task.id);
    modal.find('#taskEditName').val(task.name);
});
//* WORKING


$('#taskEditModalAjax').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');
    let modal = $(this);

    modal.find('#taskEditIdAjax').val(task.id);
    modal.find('#taskEditNameAjax').val(task.name);
    modal.find('#taskEditDescriptionAjax').val(task.description);
    modal.find('#taskEditAssignmentAjax').val(task.assignment);
});

$('#taskDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');

    let modal = $(this);

    modal.find('#taskDeleteId').val(task.id);
    modal.find('#taskDeleteName').text(task.name);
});

/**
 * Update task using ajax
 */
$(document).ready(function() {
    $('#taskEditButtonAjax').on('click', function() {
        $('#taskEditAlert').addClass('hidden');
        

        let id = $('#taskEditIdAjax').val();
        let name = $('#taskEditNameAjax').val();
        let description = $('#taskEditDescriptionAjax').val();
        let assignment = $('#taskEditAssignmentAjax').val();

        $.ajax({
            type: 'POST',
            url: '/task-update/' + id,
            data: {name: name,
                   description: description,
                   assignment: assignment}
        }).done(function(response) {
            if (response.error !== '') {
                $('#taskEditAlert').text(response.error).removeClass('hidden');
            } else {
                return redirect('/');
            }
        });
    });

    $('#taskDeleteButton').on('click', function() {
        $('#taskDeleteAlert').addClass('hidden');
        let id = $('#taskDeleteId').val();

        $.ajax({
            type: 'POST',
            url: '/task/delete/' + id,
        }).done(function(response) {
                window.location.href = "boards";
            }
        );
    });
});   
