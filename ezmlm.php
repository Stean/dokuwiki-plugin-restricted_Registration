<?php
/**
 * ezmlm-functions
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Stean <stean@gmx.org>
 */

function checkMail($mailaddress) {
    $addresses = getAddressList();
    
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

function getAddressList() {
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