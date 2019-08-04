import * as $ from "jquery";
import GalleryBlock from "@enhavo/block/Block/GalleryBlock";
import '@enhavo/block/assets/styles/block.scss';

$(() => {
    (new GalleryBlock).init(document.body);
});
