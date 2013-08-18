/*
 * For Folded Text Plugin
 *
 * @author Fabian van-de-l_Isle <webmaster [at] lajzar [dot] co [dot] uk>
 * @author Christopher Smith <chris [at] jalakai [dot] co [dot] uk>
 * @author Schplurtz le Déboulonné <schplurtz [At] laposte [doT] net>
 * @author Michael Hamann <michael@content-space.de>
 */


/*
 * run on document load, setup everything we need
 */
jQuery(function() {
    // containers for localised reveal/hide strings,
    // populated from the content set by the action plugin
    var folded_reveal = JSINFO['plugin_folded']['reveal'];
    var folded_hide = JSINFO['plugin_folded']['hide'];

    jQuery('a.folder[href*="#folded_"]').attr('title', folded_reveal);

    /*
     * toggle the folded element via className change also adjust the classname and
     * title tooltip on the folding link
     */
    jQuery('.dokuwiki .folder').click(function folded_toggle(evt) {
        var id = this.href.match(/(#.*)$/)[1];
        var $id = jQuery(id);

        if ($id.hasClass('hidden')) {
            $id.addClass('open').removeClass('hidden');
            jQuery(this)
                .addClass('open')
                .attr('title', folded_hide);
        } else {
            $id.addClass('hidden').removeClass('open');
            jQuery(this)
                .removeClass('open')
                .attr('title', folded_reveal);
        }

        evt.preventDefault();
        return false;
    });
});

// support graceful js degradation, this hides the folded blocks from view
// before they are shown,
// whilst still allowing non-js user to see any folded content.
document.write('<style type="text/css" media="screen"><!--/*--><![CDATA[/*><!--*/ .folded.hidden { display: none; } .folder .indicator { visibility: visible; } /*]]>*/--></style>');
