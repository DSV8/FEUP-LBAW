document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form');
    var submitButton = document.querySelector('button[type="submit"]');
    submitButton.disabled = true; // Initially disable the submit button

    form.addEventListener('change', function () {
        var topicSelect = document.getElementById('topic_id');
        submitButton.disabled = topicSelect.value === 'none'; // Enable/disable the submit button based on the selected value
    });

});