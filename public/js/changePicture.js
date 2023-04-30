const input = document.querySelector('#profile_picture_profilePicture');
input.addEventListener('change', () => {
    const form = input.closest('form');
    form.submit();
});
