require('./bootstrap');

// Importing jQuery
import $ from 'jquery';
window.jQuery = $;
window.$ = $;

window.previewImageOnUpload = function(input, previewHolder) {
    if (input.files && input.files[0]) {
        let image = input.files[0];
        if (image.type.startsWith('image/')) {
            let reader = new FileReader();
            reader.onload = function (e) {
                previewHolder.attr('src', e.target.result);
            }
            reader.readAsDataURL(image);
        } else {
            previewHolder.attr('src', '');
        }
    }
}
