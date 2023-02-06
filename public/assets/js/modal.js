window.addEventListener("DOMContentLoaded", function() {
    let deleteButton = document.getElementById('delete-button');
    deleteButton.addEventListener("click", function(e) {
        // prevent the form from being submitted
        e.preventDefault();

        // open a confirm dialog box
        let result = confirm("Are you sure you want to delete this feed?");
        if(result){
            // submit the #delete-form form
            document.getElementById('delete-form').submit();
        }
    });
}, false);