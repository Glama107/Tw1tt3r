// app.js

const likeCommentButtons = document.querySelectorAll('#like-comment-button');
likeCommentButtons.forEach(likeButton => {
    const articleId = likeButton.dataset.articleId;
    const commentId = likeButton.dataset.commentId;

    likeButton.addEventListener('click', () => {
        console.log('like button clicked');
        fetch(`/article/${articleId}/comment/${commentId}/like`, {
            method: 'POST',
        })
            .then(response => response.json())
            .then(data => {
                window.location.reload();
            });
    });
});