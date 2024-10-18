document.addEventListener('DOMContentLoaded', function () {
    var postcards = document.getElementsByClassName('postcard');
    for (var i = 0; i < postcards.length; i++) {
        postcards[i].addEventListener('click', function () {
            if (!event.target.matches('button')) {
                var postId = this.getAttribute('data-id');
                window.location.href = '/posts/' + postId;
            }
        });
    }
});