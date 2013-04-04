<?php
/**
 * DokuWiki Plugin restrictedregistration (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Stean <stean@gmx.org>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_restrictedregistration extends DokuWiki_Action_Plugin {

    /**
     * register the eventhandlers
     */
    public function register(Doku_Event_Handler &$controller) {
        $controller->register_hook('AUTH_USER_CHANGE', 'BEFORE', $this, 'handle_auth_user_change');
    }

    /**
     * Checks if email address is on the list
     */
    public function handle_auth_user_change(Doku_Event &$event, $param) { 
        //we are only interested in account registrations
        //TODO: What about changing the address after successful registration?
        if ($event->data["type"] == "create") {
            if (!$this->checkMail($event->data["params"][3])) {
                $event->preventDefault();
                $event->stopPropagation();
                msg($this->getLang("not_allowed_address"), -1);
            }           
        }
    }
    
    
    private function checkMail($mailaddress) {
        $addresses = $this->getAddressList();

        /*
         * Allow, if something goes wrong but the fallback action is set to "allow"
         * or if everything goes well and the entered address is found
         */
        if (($addresses == FALSE && $this->getConf("fallback_action") == 1) ||
            ($addresses != FALSE && in_array($mailaddress, $addresses))) {
                return TRUE;
            }else{
                return FALSE;
            }
        
    }

    private function getAddressList() {
        //Initialize $output
        unset($output);

        $commandstr=$this->getConf("ezmlm-binary"). " ".$this->getConf("mailinglist-subdir");
        exec($commandstr, $output, $retval);    //TODO: Replace exec by PHP-based file parsing

        if ($retval != 0) {
            //Log error
            dbglog("Execution of `$commandstr` failed with returnvalue $retval and output \"$output\"");
            return FALSE;
        }else{
            return $output;
        }
    }
}

// vim:ts=4:sw=4:et:
