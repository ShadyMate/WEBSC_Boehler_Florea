$(document).ready(function () {
    if (!sessionStorage.getItem('username')) {
        var username = null;
        // Keep asking for the user's name until a valid name is entered
        while (!username || username.trim() === '') {
            username = prompt("Please enter your name");
        }
        // Save the username in sessionStorage
        sessionStorage.setItem('username', username);
    }

    // Send GET request to get the existing dates
    var dataToSend = {
        action: 'queryAppointments',
        param: 'hi'
    };

    // Retrieving data-id and title attribute from the table row
    $(document).on('click', 'tr', function () {
        // Get the id from the data-id attribute
        var id = $(this).data('id');

        // Get the title from the first cell of the row
        var title = $(this).find('td:first').text();

        // Store the id in the form
        $('#voteForm').data('id', id);

        // Set the title in the modal header
        $('#voteModal .modal-title').text(title);

        // Show the modal
        $('#voteModal').modal('show');
    });

    $(document).on('submit', '#voteForm', function(e) {
        // Prevent the form from submitting normally
        e.preventDefault();
    
        // Get the id from the data-id attribute
        var id = $(this).data('id');
    
        // Get the vote and comment
        var vote = $('#vote').val();
        var comment = $('#comment').val();
    
        // AJAX call to submit the vote and comment
        $.ajax({
            type: "POST",
            url: "../backend/businesslogic/simpleLogic.php",
            data: { action: 'submitVote', id: id, vote: vote, comment: comment },
            success: function(response) {
                // Handle success
                console.log(response);
                $('#voteModal').modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle error
                console.error(textStatus, errorThrown);
            }
        });
    });

    // Retrieving data-id attribute from the table row
    $(document).on('click', 'tr', function() {
        // Get the id from the data-id attribute
        var id = $(this).data('id');
    
        // Store the id in the form
        $('#voteForm').data('id', id);
    
        // Show the modal
        $('#voteModal').modal('show');
    });

    // First AJAX call to get the dates
    $.ajax({
        type: "GET",
        url: "../backend/businesslogic/simpleLogic.php",
        cache: false,
        data: dataToSend,
        dataType: "json",
        success: function (response) {
            // Check if there are any dates
            if (response.length > 0) {
                // Hide the no dates message
                $('#noDatesMessage').hide();

                // Create table and add headers
                var table = $('<table>').appendTo('#datesContainer');
                $('<tr>').appendTo(table)
                    .append('<th>Title</th>')
                    .append('<th>Location</th>')
                    .append('<th>Date</th>')
                    .append('<th>Time</th>')

                // Add a row for each object in the response
                for (var i = 0; i < response.length; i++) {
                    var id = response[i].id;
                    var title = response[i].titel;
                    var location = response[i].ort;
                    var date = response[i].durationDate;
                    var time = response[i].durationTime;
                    var row = $('<tr>').appendTo(table)
                        .data('id', id)  // Store the id
                        .append('<td>' + title + '</td>')
                        .append('<td>' + location + '</td>')
                        .append('<td>' + date + '</td>')
                        .append('<td>' + time + '</td>');
                }
            }
        }
    });

    // Event handler for delete button click
    $(document).on('click', '#deleteButton', function() {
        // Get the id from the data-id attribute
        var id = $('#voteForm').data('id');
    
        // AJAX call to delete the appointment
        $.ajax({
            type: "POST",
            url: "../backend/businesslogic/simpleLogic.php",
            data: { action: 'queryDeleteAppointment', param: id },
            success: function(response) {
                // Handle success
                console.log(response);
                $('#voteModal').modal('hide');

                // Refresh the page
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle error
                console.error(textStatus, errorThrown);
            }
        });
    });

    var selectedDate;

    // Initialize datepicker
    $("#calendar").datepicker({
        onSelect: function (dateText, inst) {
            selectedDate = dateText;
            $(inst.dpDiv).find(".ui-state-active").addClass("ui-state-highlight");
        }
    });

    // Show calendar and form when "Create New Date" button is clicked
    $("#createDate").click(function () {
        $("#calendar").show();
        $("#timeForm").show();
    });

    // Hide calendar and form and log date and times when "Confirm" button is clicked
    $("#confirm").click(function () {
        var title = $("#title").val();
        var place = $("#place").val();
        var info = $("#info").val();
        var startTime = $("#start").val();
        var endTime = $("#end").val();

        // Create Date objects for start and end times
        var start = new Date(selectedDate + ' ' + startTime);
        var end = new Date(selectedDate + ' ' + endTime);

        // Calculate duration in hours
        var duration = (end - start) / 1000 / 60 / 60;

        // Prepare data to send
        var dataToSend = {
            action: 'queryPostAppointment',
            param: title + ',' + place + ',' + duration + ',' + info
        };

        console.log("Data to be sent: ", dataToSend);

        // Make AJAX call
        $.ajax({
            type: "POST",
            url: "../backend/businesslogic/simpleLogic.php",
            cache: false,
            data: dataToSend,
            dataType: "json",
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    console.log(response);
                } catch (error) {
                    console.error("Error parsing response as JSON:", error);
                }
            }
        });
    });
    function checkExpiredAppointments() {
        // Get all appointment rows
        var rows = $('#datesContainer tr');

        // Get current date and time
        var now = new Date();

        // Iterate over each row
        rows.each(function() {
            // Get date and time from the row
            var date = new Date($(this).find('td:nth-child(3)').text() + ' ' + $(this).find('td:nth-child(4)').text());

            // Check if the date and time is in the past
            if (date < now) {
                // Add 'expired' class to the row
                $(this).addClass('expired');
            }
        });
    }

// Call the function when the document is ready
    $(document).ready(function() {
        checkExpiredAppointments();
    });
});
