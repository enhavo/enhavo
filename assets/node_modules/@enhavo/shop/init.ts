import * as $ from "jquery";
import '@enhavo/shop/assets/styles/block.scss';
import '@enhavo/shop/assets/styles/shop.scss';
import '@enhavo/shop/assets/styles/user.scss';
import Cart from "@enhavo/shop/cart/Cart";

$(() => {
    (new Cart).init(document.body);
});
