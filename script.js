$('.date').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true,
    todayHighlight: true,
    showOtherMonths: true
}).datepicker("setDate", new Date());

window.onload = function () {
    /* Show the toast */
    var toast = document.getElementById("toast");
    toast.innerHTML = "Copy text button is now functional...!"; // Set the toast message
    toast.className = "toast show";
    setTimeout(function () {
        toast.className = toast.className.replace("show", "");
    }, 3000);
}

function myFunction() {
    /* Get the text field */
    var copyText = document.getElementById("commits");

    /* Create a temporary textarea to select the text from */
    var tempElement = document.createElement('textarea');
    tempElement.value = copyText.textContent.trim(); // Use trim() here
    document.body.appendChild(tempElement);

    /* Select the text */
    tempElement.select();

    /* Copy the text */
    try {
        document.execCommand('copy');
        /* Show the toast */
        var toast = document.getElementById("toast");
        toast.innerHTML = "Copied!"; // Set the toast message
        toast.className = "toast show";
        setTimeout(function () {
            toast.className = toast.className.replace("show", "");
        }, 3000);
    } catch (err) {
        console.error('Could not copy text: ', err);
    }

    /* Remove the temporary textarea */
    document.body.removeChild(tempElement);
}