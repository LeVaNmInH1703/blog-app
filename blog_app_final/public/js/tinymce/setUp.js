function setUpTinymce(){
    const content = document.querySelector('#create-blog-content');
            tinymce.init({
                selector: '#tinymce-content',
                setup: function(editor) {
                    editor.on('init', function() {
                        editor.focus();
                        editor.on('blur', () => {
                            if (!tinymce.get('tinymce-content').getContent()) {
                                document.querySelector('#tinymce-container').style
                                    .display = 'none';
                                document.querySelector(
                                        '.create_post_option .btn-submit').style
                                    .display = 'none';
                                content.style.display = 'block';
                            }
                        })
                    });
                    editor.on('input', function(editor) {
                        content.value = tinymce.get('tinymce-content').getContent();
                    })
                },
                skin: 'oxide-dark',
            });
            content.addEventListener('focus', function() {
                document.querySelector('#tinymce-container').style.display = 'flex';
                document.querySelector('.create_post_option .btn-submit').style.display = 'block';
                content.style.display = 'none';
                tinymce.get('tinymce-content').focus();
            });
}