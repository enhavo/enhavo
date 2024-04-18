import $ from "jquery";
import '@enhavo/media/assets/styles/form/media.scss';
import {Media} from '@enhavo/media/form/Media';

$(() => {
    $('[data-media]').each(function () {
        new Media(this);
    })
});


