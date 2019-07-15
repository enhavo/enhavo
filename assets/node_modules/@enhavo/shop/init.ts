import * as $ from "jquery";
import '@enhavo/shop/assets/styles/block.scss';
import '@enhavo/shop/assets/styles/cart.scss';
import '@enhavo/shop/assets/styles/checkout.scss';
import Cart from "@enhavo/shop/Cart/Cart";

$(() => {
    (new Cart).init(document.body);
});
