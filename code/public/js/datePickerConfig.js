function initializeDatePickers() {
    flatpickr("#minimum_date", {
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            // Handle selection of minimum date
        }
    });

    flatpickr("#maximum_date", {
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            // Handle selection of maximum date
        }
    });
}