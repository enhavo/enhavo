import InitializerInterface from "@enhavo/app/InitializerInterface";
import * as $ from "jquery";

export default class GalleryBlock implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(element).find('[data-slider]').slick({
            adaptiveHeight: true,
            dots:true,
            nextArrow: '<div class="arrow next"></div>',
            prevArrow: '<div class="arrow prev"></div>'
        });
    }
}