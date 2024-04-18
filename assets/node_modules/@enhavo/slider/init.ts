import $ from "jquery";
import '@enhavo/slider/assets/styles/block.scss';
import SliderBlock from "@enhavo/slider/block/SliderBlock";

$(() => {
    (new SliderBlock).init(document.body);
});
