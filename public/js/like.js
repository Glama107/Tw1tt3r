// app.js

const likeButtons = document.querySelectorAll('#like-button');
const likedImage = document.querySelectorAll('#liked-image');

likeButtons.forEach(likeButton => {
    const articleId = likeButton.dataset.articleId;

    likeButton.addEventListener('click', () => {
        fetch(`/article/${articleId}/like`, {
            method: 'POST',
        })
            .then(response => response.json())
            .then(data => {
                const likesCount = likeButton.parentNode.querySelector('#like-count');
                likesCount.textContent = data.likes;
                window.location.reload();
            });
    });
});