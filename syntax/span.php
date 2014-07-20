<?php
/**
 * Folded text Plugin: enables folded text font size with syntax ++ text ++
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Fabian van-de-l_Isle <webmaster [at] lajzar [dot] co [dot] uk>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Esther Brunner <esther@kaffeehaus.ch>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

// maintain a global count of the number of folded elements in the page, 
// this allows each to be uniquely identified
global $plugin_folded_count;
if (!isset($plugin_folded_count)) $plugin_folded_count = 0;

// global used to indicate that the localised folder link title tooltips 
// strings have been written out
global $plugin_folded_strings_set;
if (!isset($plugin_folded_string_set)) $plugin_folded_string_set = false;

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_folded_span extends DokuWiki_Syntax_Plugin {

    function getType(){ return 'formatting'; }
    function getAllowedTypes() { return array('substition','protected','disabled','formatting'); }
    function getSort(){ return 405; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('\+\+.*?\|(?=.*\+\+)',$mode,'plugin_folded_span'); }
    function postConnect() { $this->Lexer->addExitPattern('\+\+','plugin_folded_span'); }

   /**
    * Handle the match
    */
    function handle($match, $state, $pos, Doku_Handler $handler){
        if ($state == DOKU_LEXER_ENTER){
            $match = trim(substr($match,2,-1)); // strip markup
        } else if ($state == DOKU_LEXER_UNMATCHED) {
            $handler->_addCall('cdata',array($match), $pos);
            return false;
        }
        return array($state, $match);
    }

   /**
    * Create output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        global $plugin_folded_count;

        if (empty($data)) return false;
        list($state, $cdata) = $data;

        if($mode == 'xhtml') {
            switch ($state){
               case DOKU_LEXER_ENTER:
                $plugin_folded_count++;
                $renderer->doc .= '<a class="folder" href="#folded_'.$plugin_folded_count.'">';

                if ($cdata)
                    $renderer->doc .= ' '.$renderer->cdata($cdata);

                $renderer->doc .= '</a><span class="folded hidden" id="folded_'.$plugin_folded_count.'">';
                break;
                
              case DOKU_LEXER_UNMATCHED:
                $renderer->cdata($cdata);
                break;
                
              case DOKU_LEXER_EXIT:
                $renderer->doc .= '</span>';
                break;
            }
            return true;
        } else {
            if ($cdata) $renderer->cdata($cdata);
        }
        return false;
    }
}
