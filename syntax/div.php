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
class syntax_plugin_folded_div extends DokuWiki_Syntax_Plugin {

    function getType(){ return 'container'; }
    function getPType() { return 'block'; }
    function getAllowedTypes() { return array('container', 'substition', 'protected', 'disabled', 'paragraphs', 'formatting'); }
    function getSort(){ return 404; }
    function connectTo($mode) {
        // addEntryPattern
        //     \+{4}            matches the character +, 4 times
        //     [^\+]*?          matches any character except +, between 0 and infinity times
        //     \|               matches the character |
        //     (?=.*?\+{4})     positive lookahead: checks if there are four +
        // addPattern
        //     \+{3}            matches the character +, 3 times
        //     [^\+]*?          matches any character except +, between 0 and infinity times
        //     \|               matches the character |
        // addPattern
        //    (?<![\+])         negative lookbehind: doesn't match if before there is a +
        //    \+{3}             matches the character +, 3 times
        //    (?![\+])          negative lookahead: doesn't match if bafter there is a +
        //    (?![\s\w]*?\|)    negative lookahead: doesn't match if after there is a |
        $this->Lexer->addEntryPattern('\+{4}[^\+]*?\|(?=.*?\+{4})', $mode, 'plugin_folded_div');
        $this->Lexer->addPattern('\+{3}[^\+]*?\|', 'plugin_folded_div');
        $this->Lexer->addPattern('(?<![\+])\+{3}(?![\+])(?![\s\w]*?\|)', 'plugin_folded_div');
    }

    function postConnect() {
        // addExitPattern
        //     \+{4}            matches the character +, 4 times
        //    (?![\s\w]*?\|)    negative lookahead: doesn't match if after there is a |
        $this->Lexer->addExitPattern('\+{4}(?![\s\w]*?\|)', 'plugin_folded_div');
    }

   /**
    * Handle the match
    */
    function handle($match, $state, $pos, Doku_Handler $handler){
        if ($state == DOKU_LEXER_ENTER) {
            $match = trim(substr($match, 4, -1)); // strip markup
        }
        else if ($state == DOKU_LEXER_MATCHED) {
            if ($match !== '+++') {
                $match = trim(substr($match, 3, -1)); // strip markup
            }
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

        switch ($state) {
            case DOKU_LEXER_ENTER:
                $plugin_folded_count++;
                $renderer->doc .= '<p><a class="folder" href="#folded_' . $plugin_folded_count . '">';
                if ($cdata) {
                    $renderer->doc .= ' ' . $renderer->cdata($cdata);
                }
                $renderer->doc .= '</a></p><div class="folded hidden" id="folded_' . $plugin_folded_count . '">';
                break;
            case DOKU_LEXER_MATCHED:
                if ($cdata !== '+++') {
                    $plugin_folded_count++;
                    $renderer->doc .= '<p><a class="folder" href="#folded_' . $plugin_folded_count . '">';
                    if ($cdata) {
                        $renderer->doc .= ' ' . $renderer->cdata($cdata);
                    }
                    $renderer->doc .= '</a></p><div class="folded hidden" id="folded_' . $plugin_folded_count . '">';
                    break;
                }
                else {
                    $renderer->doc .= '</div>';
                    break;
                }
            case DOKU_LEXER_UNMATCHED: // defensive, shouldn't occur
                $renderer->p_open();
                $renderer->cdata($cdata);
                $renderer->p_close();
                break;
            case DOKU_LEXER_EXIT:
                $renderer->doc .= '</div>';
                break;
        }
        return true;
    }
}
