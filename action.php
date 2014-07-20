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
}
