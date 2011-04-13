/*
 * For Folded Text Plugin
 *
 * @author Fabian van-de-l_Isle <webmaster [at] lajzar [dot] co [dot] uk>
 * @author Christopher Smith <chris [at] jalakai [dot] co [dot] uk>
 */

// containers for localised reveal/hide strings, 
// populated from html comments in hidden elements on the page
var folded_reveal = 'reveal';
var folded_hide = 'hide';

/*
 * toggle the folded element via className change
 * also adjust the classname and title tooltip on the folding link
 */
function folded_toggle(evt) {
  id = this.href.match(/#(.*)$/)[1];
  e = $(id);
  if (!e) return;

  if (e.className.match(/\bhidden\b/)) {
    e.className = e.className.replace(/\bhidden\b/g,'');
    e.className = e.className.replace(/  /g,' ');

    this.title = folded_hide;

    this.className += ' open';
  } else {
    e.className += ' hidden';

    this.title = folded_reveal;

    this.className = this.className.replace(/\bopen\b/g,'');
    this.className = this.className.replace(/  /g,' ');
  }

  evt.preventDefault();
  return false;
}

/*
 * run on document load, setup everything we need
 */
function folded_setup() {
  
  // extract and save localised title tooltip strings
  var eStrings = $('folded_reveal','folded_hide');
  if (!eStrings[0]) return;

  folded_reveal = eStrings[0].innerHTML.match(/^<!-- (.*) -->$/)[1];
  folded_hide = eStrings[1].innerHTML.match(/^<!-- (.*) -->$/)[1];

  // find all folder links, assign onclick handler and title tooltip for initial state
  var folds = getElementsByClass('folder');
  for (var i=0; i<folds.length; i++) {    
    addEvent(folds[i], 'click', folded_toggle);
    folds[i].title = folded_reveal;
  }
}

addInitEvent(folded_setup);

// support graceful js degradation, this hides the folded blocks from view before they are shown, 
// whilst still allowing non-js user to see any folded content.
document.write('<style type="text/css" media="screen"><!--/*--><![CDATA[/*><!--*/ .folded.hidden { display: none; } .folder .indicator { visibility: visible; } /*]]>*/--></style>');
