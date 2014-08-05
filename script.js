/*
 * For Folded Text Plugin
 *
 * @author Ikuo Obataya <i.obataya [at] gmail.com>
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

    jQuery('a.folder').attr('title', folded_reveal);


    // Click event for a.folder.
    jQuery("a.folder").click(function() {
       // index for a.folder and div.folded
        var num = jQuery("a.folder").index(this);
        var item = jQuery("div.folded").eq(num);
        if(item.hasClass('hidden')){
          item.addClass('open').removeClass('hidden');
          item.addClass('open')
              .attr('title', folded_hide);
        }else{
          item.addClass('hidden').removeClass('open');
          item.removeClass('open')
              .attr('title', folded_reveal);
        }
    });
});

// support graceful js degradation, this hides the folded blocks from view
// before they are shown,
// whilst still allowing non-js user to see any folded content.
document.write('<style type="text/css" media="screen"><!--/*--><![CDATA[/*><!--*/ .folded.hidden { display: none; } .folder .indicator { visibility: visible; } /*]]>*/--></style>');
