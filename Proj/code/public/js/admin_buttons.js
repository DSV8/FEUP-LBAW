function block(id) {
    const $id = id;
    const buttonBlock = document.getElementById($id);
    if (buttonBlock.className === "not-clicked") {
        buttonBlock.className = "clicked";
        buttonBlock.innerHTML = 'Unblock';
        fetch('/profile/block', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            console.log(response);
            if (response.headers.get('Content-Type').includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Server response is not JSON');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        })
        .catch(error => {
            console.log('Failed to block the user:', error);
        });
    }
    else {
        buttonBlock.className = "not-clicked";
        buttonBlock.innerHTML = 'Block';
        fetch('/profile/unblock', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            console.log(response);
            if (response.headers.get('Content-Type').includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Server response is not JSON');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        })
        .catch(error => {
            console.log('Failed to block the user:', error);
        });
    }
}