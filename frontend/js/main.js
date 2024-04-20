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
    $.get("http://localhost/backend/db/dataHandler.php", { action: 'getDates' }, function(data) {
        // Parse the data into a JavaScript object
        var dates = JSON.parse(data);

        // Check if there are any dates
        if (dates.length > 0) {
            // Hide the no dates message
            $('#noDatesMessage').hide();

            // Add each date to the datesContainer div
            for (var i = 0; i < dates.length; i++) {
                $('#datesContainer').append('<p>' + dates[i] + '</p>');
            }
        }
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
        console.log("Title: " + title + ", Place: " + place + ", Info: " + info + ", Date: " + selectedDate + ", Start Time: " + startTime + ", End Time: " + endTime);
        $("#calendar").hide();
        $("#timeForm").hide();

        // Send POST request
        $.post("http://localhost/backend/db/dataHandler.php", { action: 'queryPostAppointment', param: title + ',' + place + ',' + hoursToAdd + ',' + info }, function (data) {
            console.log("Data inserted: " + data);
        });
    });
});