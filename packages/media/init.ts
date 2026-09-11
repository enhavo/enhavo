import $ from "jquery";
import {Media} from '@enhavo/media/form/Media';

$(() => {
    $('[data-media]').each(function () {
        new Media(this);
    })
});


