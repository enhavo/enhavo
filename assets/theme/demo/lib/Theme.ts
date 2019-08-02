import * as $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class Theme implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(() => {
            this.initNavigation(element);
            this.showDiffBillAddressForm(element);
            this.handleLoadingCursor(element);
        })
    }

    private initNavigation(element: HTMLElement)
    {
        $(element).find('[data-show-menu]').on('click', function() {
            $('[data-menu-items]').toggle();
            $(this).toggleClass('active');
        });
    }

    private showDiffBillAddressForm(element: HTMLElement)
    {

        $(element).find('[data-billaddress-checkbox]').on('change', function() {
            $(this).parent().parent().find('fieldset').fadeToggle();
        });
    }

    private handleLoadingCursor(element: HTMLElement)
    {
        $('[data-loading-screen]').on('mouseover', function() {
            $(this).find('[data-loading-spinner]').fadeIn();
            document.onmousemove = handleMouseMove;
            function handleMouseMove(event) {

                let eventDoc, doc, body;
                event = event || window.event;

                if (event.pageX == null && event.clientX != null) {
                    eventDoc = (event.target && event.target.ownerDocument) || document;
                    doc = eventDoc.documentElement;
                    body = eventDoc.body;

                    event.pageX = event.clientX +
                        (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
                        (doc && doc.clientLeft || body && body.clientLeft || 0);
                    event.pageY = event.clientY +
                        (doc && doc.scrollTop  || body && body.scrollTop  || 0) -
                        (doc && doc.clientTop  || body && body.clientTop  || 0 );
                }

                console.log('X:'+event.pageX);
                console.log(event.pageY);

                document.getElementsByClassName('loading')[0].style.top=event.pageY+'px';
                document.getElementsByClassName('loading')[0].style.left=event.pageX+'px';
            }
        });
        $('[data-loading-screen]').on('mouseleave', function() {
            $('[data-loading-spinner]').fadeOut();
        });
    }


}