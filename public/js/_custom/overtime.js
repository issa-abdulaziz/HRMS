$('#employee_id').change(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "/overtime/getHourlyPrice",
        data: {employee_id:$('#employee_id').val()},
        dataType: 'json',
        success: function (response) {
            $('#hourly_price').val(response.hourly_price.toFixed(2));
            $('#salary').val(response.salary);
            $('#working_hour').val(response.working_hour);
            $('#date').attr('min', response.hired_at);
            getAmount();
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
});

$('#date').change(getRate);
getRate();

$('#hour').change(function() {
    getAmount();
});

$('#minutes').change(function() {
    getAmount();
});

$('#rate').change(function() {
    getAmount();
});

function getAmount() {
    let hourly_price = $('#hourly_price').val();
    let rate = $('#rate').val();
    let hour = $('#hour').val();
    let minutes = $('#minutes').val();
    if (hourly_price && rate && hour && minutes) {
        let time = parseInt(hour) + parseInt(minutes) / 60;
        $('#time').val(parseInt(hour) * 60 + parseInt(minutes)); // time should be stored in minutes
        $('#amount').val(Math.ceil(hourly_price * rate * time));
    }
}

function getRate() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "/overtime/getRate",
        data: {date:$('#date').val()},
        dataType: 'json',
        success: function (response) {
            $('#rate').val(response.rate);
            getAmount();
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}
