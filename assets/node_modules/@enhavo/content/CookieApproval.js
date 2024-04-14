function CookieApproval() {
    this.handleCookieAccessLevel = function (accessLevel) {
        let relevantElements = $('[data-cookie-access]');

        relevantElements.each(function (index) {
            let elem = $(this);
            let elemAccess = elem.attr('data-cookie-access');
            if (elemAccess == accessLevel) {
                let tagName = elem.prop('tagName').toLowerCase();
                if (tagName == 'script') {
                    // remove, change several attributes and append to head to activate element
                    elem.remove();

                    if (elem.attr('data-src')) {
                        elem.attr('src', elem.attr('data-src'))
                    }
                    if (elem.attr('data-type')) {
                        elem.attr('type', elem.attr('data-type'));
                    }

                    $('head').prepend(elem);
                }
            }
        });
    };
}
