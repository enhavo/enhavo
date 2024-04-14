import InitializerInterface from "@enhavo/app/InitializerInterface";
import $ from "jquery";
import "slick-carousel";

export default class GalleryBlock implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        if($('[data-slider]').length > 0) {
            $(element).find('[data-slider]').slick({
                adaptiveHeight: true,
                dots: true,
                nextArrow: '<div class="arrow next"></div>',
                prevArrow: '<div class="arrow prev"></div>'
            });
        }
    }
}