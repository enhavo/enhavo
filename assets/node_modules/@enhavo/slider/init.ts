import * as $ from "jquery";
import '@enhavo/slider/assets/styles/block.scss';
import SliderBlock from "@enhavo/slider/Block/SliderBlock";

$(() => {
    (new SliderBlock).init(document.body);
});
