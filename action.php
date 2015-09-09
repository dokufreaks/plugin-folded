<?php
/**
 * Folded plugin: enables folded text font size with syntax ++ text ++
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Michael Hamann <michael@content-space.de>
 */
if(!defined('DOKU_INC')) die();  // no Dokuwiki, no go

/**
 * Action part: makes the show/hide strings available in the browser
 */
class action_plugin_folded extends DokuWiki_Action_Plugin {
    /**
     * Register the handle function in the controller
     *
     * @param Doku_event_handler $controller The event controller
     */
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'addhidereveal');
        $controller->register_hook('TPL_CONTENT_DISPLAY', 'BEFORE', $this, 'rewriteTitle', array());
    }

    /**
     * Add the hide and reveal strings to $JSINFO so it can be used in the javascript
     *
     * @param Doku_Event $event  The event
     * @param array      $params The parameters for the event
     */
    function addhidereveal($event, $params) {
        global $JSINFO;

        $hide = $this->getConf('hide') ? $this->getConf('hide') : $this->getLang('hide');
        $reveal = $this->getConf('reveal') ? $this->getConf('reveal') : $this->getLang('reveal');

        $JSINFO['plugin_folded'] = array(
            'hide' => $hide,
            'reveal' => $reveal
        );
    }

    /**
     * Rewrite the tags for the titles
     *
     * @param Doku_Event $event  The event
     * @param array      $params The parameters for the event
     */
    function rewriteTitle($event, $params) {
        // Each title matched is splitted in an array like this:
        //     array {
        //         [1] => <p>
        //         [2] => \s or \t between zero or unlimited times
        //         [3] => = between one or unlimited times
        //         [4] => title
        //         [5] => = between one or unlimited times
        //         [6] => \s or \t between zero or unlimited times
        //         [7] => </p>
        //     }
        // The relation between the number of equal signs (=) and the relative
        // tag <h*> is the following:
        //    <h1>  | <h2>  | <h3>  | <h4>  | <h5>
        //    = * 6 | = * 5 | = * 4 | = * 3 | = * 2
        $re = '/(<p>)([\s\n]*)([=]+)(.*?)([=]+)([\s\n]*)(<\/p>)/';
        $xhtml = $event->data;

        function rewriteTagTitle($match) {
            $level = array (
                '======' => '1',
                '=====' => '2',
                '====' => '3',
                '===' => '4',
                '==' => '5'
            );
            if ($match[3] === $match[5]) {
                $match[1] = '<h' . $level[$match[3]] . '>';
                $match[7] = '</h' . $level[$match[5]] . '>';
                $match[3] = '';
                $match[5] = '';
            }
            return $match[1] . $match[2] . $match[3] . $match[4] . $match[5] . $match[6] . $match[7];
        }

        $event->data = preg_replace_callback($re, 'rewriteTagTitle', $xhtml);
    }
}
