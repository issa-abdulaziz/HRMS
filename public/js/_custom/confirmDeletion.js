
$('#confirmDeletionModal').on('show.bs.modal', function(event) {
let button = $(event.relatedTarget);
let title = $('#modalTitle').val();
let modal = $(this);
let modalBody = modal.find('.modal-body');
modalBody.empty();

let id = button.data('id');
let name = button.data('name');
let date = button.data('date');
let dateTo = button.data('dateto');
let amount = button.data('amount');
let currency = button.data('currency');

if (name)
    modalBody.append('<p><strong>Name: </strong> ' + name + '</p>');
if (date && title == 'employee')
    modalBody.append('<p><strong>Hired At: </strong> ' + date + '</p>');
else if (date)
    modalBody.append('<p><strong>Date: </strong> ' + date + '</p>');
if (dateTo)
    modalBody.append('<p><strong>To: </strong> ' + dateTo + '</p>');
if (amount && currency)
    modalBody.append('<p><strong>Amount: </strong> ' + amount + ' ' + currency + '</p>');

modal.find('#deletionForm').attr('action', '/' + title + '/' + id);
});