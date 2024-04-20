$(document).ready(function () {
    var name = prompt("Please enter your name");
    while (name === null || name === "") {
        name = prompt("Please enter your name");
    }

    // Save name to session
    sessionStorage.setItem("name", name);

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