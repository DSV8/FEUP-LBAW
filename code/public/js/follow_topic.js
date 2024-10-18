
function toggleFollow(topicId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/topics/${topicId}/toggle-follow`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            // Include any necessary headers for authentication if needed
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        throw new Error('Network response was not ok.');
    })
    .then(data => {
        const buttons = document.querySelectorAll(`.followButton[data-topic-id="${topicId}"]`);
        buttons.forEach(button => {
            if (data.message === 'Followed') {
                button.textContent = 'Unfollow ' + data.topicTitle;
            } else if (data.message === 'Unfollowed') {
                button.textContent = 'Follow ' + data.topicTitle;
            }
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error.message);
        // Handle error responses
    });
}