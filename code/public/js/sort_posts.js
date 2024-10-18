document.getElementById('sortForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    // Redirect to the selected option's value
    window.location.href = document.getElementById('sortSelector').value;
});
