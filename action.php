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
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handle_toolbar', array ());
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
    function handle_toolbar(&$event, $param) {
        $event->data[] = array (
                'type' => 'picker',
                'title' => 'Folded plugin',
                'icon' => '../../plugins/folded/img/closed_div.png',
                'list' => array(
                        array(
                                'type'   => 'format',
                                'title'  => 'folder div',
                                'icon'   => '../../plugins/folded/img/closed_div.png',
                                'open'   => '++++ ',
                                'close'  => '|\n(folded text)\n++++\n',
                        ),
                        array(
                                'type'   => 'format',
                                'title'  => 'folder span',
                                'icon'   => '../../plugins/folded/img/closed_span.png',
                                'open'   => '++',
                                'close'  => '|(folded text)++',
                        ),
                ),

        );

    }
}
