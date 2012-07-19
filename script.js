/*
 * For Folded Text Plugin
 *
 * @author Fabian van-de-l_Isle <webmaster [at] lajzar [dot] co [dot] uk>
 * @author Christopher Smith <chris [at] jalakai [dot] co [dot] uk>
 * @author Schplurtz le Déboulonné <schplurtz [At] laposte [doT] net>
 */

jQuery(function($) {
	/*
	 * run on document load, setup everything we need
	 */

	// containers for localised reveal/hide strings,
	// populated from html comments in hidden elements on the page
	// TODO: is there better way?
	var folded_reveal = $('#folded_reveal').html().match(/^<!-- (.*) -->$/)[1] || 'reveal';
	var folded_hide = $('#folded_hide').html().match(/^<!-- (.*) -->$/)[1] || 'hide';

	/*
	 * toggle the folded element via className change also adjust the classname and
	 * title tooltip on the folding link
	 */
	function folded_toggle(evt) {
		var id = this.href.match(/(#.*)$/)[1];
		var $id = $(id);

		if ($id.hasClass('hidden')) {
			$id.addClass('open').removeClass('hidden');
		} else {
			$id.addClass('hidden').removeClass('open');
		}

		evt.preventDefault();
		return false;
	}

    $('.dokuwiki .folder').click(folded_toggle);
});

// support graceful js degradation, this hides the folded blocks from view
// before they are shown,
// whilst still allowing non-js user to see any folded content.
document.write('<style type="text/css" media="screen"><!--/*--><![CDATA[/*><!--*/ .folded.hidden { display: none; } .folder .indicator { visibility: visible; } /*]]>*/--></style>');
