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
    if(!JSINFO || !JSINFO['plugin_folded']) return;
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

function fold_unfold_all() {
    var hide = -1;
    var cpt = 1;

    var folders = document.getElementsByClassName("folder");
    var i;
    for (i = 0; i < folders.length; i++) {
        // initially, find out whether we want to hide or unhide
        if (hide == -1) {
            if (folders[i].className.search("open") == -1) {
                hide = 0;
            } else {
                hide = 1;
            }
        }

        if (hide == 1) {
            folders[i].className = folders[i].className.replace(/open/g, "");
        } else {
            folders[i].className = folders[i].className + " open";
        }

    }

    // get first folded_ object
    var obj = document.getElementById("folded_" + cpt++);
    while ( obj != null ) {
        if (hide == 1) {
            obj.className = obj.className.replace(/open/g, "");
            obj.className = obj.className + " hidden";
        } else {
            obj.className = obj.className.replace(/hidden/g, "");
            obj.className = obj.className + " open";
        }

        // get next folded_ object
        obj = document.getElementById("folded_" + cpt++);
    }
}

// support graceful js degradation, this hides the folded blocks from view
// before they are shown,
// whilst still allowing non-js user to see any folded content.
document.write('<style type="text/css" media="screen"><!--/*--><![CDATA[/*><!--*/ .folded.hidden { display: none; } .folder .indicator { visibility: visible; } /*]]>*/--></style>');
