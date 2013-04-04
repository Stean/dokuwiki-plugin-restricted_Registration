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
require_once 'ezmlm.php';

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
           if (!checkMail($event->data["params"][3])) {
               $event->preventDefault();
               $event->stopPropagation();
               msg($this->getLang("not_allowed_address"), -1);
           }           
       }
    }
}

// vim:ts=4:sw=4:et:
