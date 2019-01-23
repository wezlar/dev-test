/*
 * Bootstrap popovers and tooltips
 */


var $ = require('jquery')


module.exports = function () {

    // initialise all popovers
    $('body').popover({
        selector: '[data-toggle="popover"]',
        container: 'body',
        trigger: 'focus',
        viewport: { selector: 'body', padding: 20 }
    })

    // Initialise Bootstrap tooltips
    //http://getbootstrap.com/javascript/#tooltips
    $('[data-toggle="tooltip"]').tooltip()
}
