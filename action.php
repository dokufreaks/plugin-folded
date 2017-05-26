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
        $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'add_button', array());
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
     * Add 'fold/unfold all'-button to pagetools
     *
     * @param Doku_Event $event
     * @param mixed      $param not defined
     */
    public function add_button(&$event, $param) {
        global $ID, $REV;

        if($this->getConf('show_fold_unfold_all_button') && $event->data['view'] == 'main') {
            $params = array('do' => 'fold_unfold_all');
            if($REV) $params['rev'] = $REV;

            // insert button at position before last (up to top)
            $event->data['items'] = array_slice($event->data['items'], 0, -1, true) +
                                    array('fold_unfold_all' =>
                                          '<li>'
                                          .'<a href="javascript:void(0);" class="fold_unfold_all" onclick="fold_unfold_all();" rel="nofollow" title="'.$this->getLang('fold_unfold_all_button').'">'
                                          .'<span>'.$this->getLang('fold_unfold_all_button').'</span>'
                                          .'</a>'
                                          .'</li>'
                                    ) +
                                    array_slice($event->data['items'], -1 , 1, true);
        }
    }
}
