document.addEventListener('DOMContentLoaded', function() {
    const searchCategory = document.getElementById('search_category');
    const searchTerm = document.getElementById('search_term'); // Updated variable name

    searchCategory.addEventListener('change', function() {
        const selectedOption = searchCategory.options[searchCategory.selectedIndex].text;
        searchTerm.placeholder = `Search for ${selectedOption}`;
    });

    const searchButton = document.getElementById('search_button');

    searchButton.addEventListener('click', function() {
        const selectedOption = searchCategory.options[searchCategory.selectedIndex].value;
        const searchTermValue = searchTerm.value.trim(); // Renamed variable

        if (selectedOption === 'posts' && searchTermValue !== '') {
            // Construct the URL with the search term as a request parameter
            const redirectURL = `/post_search?search_term=${encodeURIComponent(searchTermValue)}`;
            window.location.href = redirectURL;
        }
        else if (selectedOption === 'users' && searchTermValue !== '') {
            const redirectURL = `/user_search?search_term=${encodeURIComponent(searchTermValue)}`;
            window.location.href = redirectURL;
        }
        else if (selectedOption === 'comments' && searchTermValue !== '') {
            const redirectURL = `/comment_search?search_term=${encodeURIComponent(searchTermValue)}`;
            window.location.href = redirectURL;
        }
        // Add conditions for other search options (users, comments) if needed
    });
});