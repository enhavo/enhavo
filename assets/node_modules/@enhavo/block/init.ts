import $ from "jquery";
import GalleryBlock from "@enhavo/block/block/GalleryBlock";
import '@enhavo/block/assets/styles/block.scss';

$(() => {
    (new GalleryBlock).init(document.body);
});
