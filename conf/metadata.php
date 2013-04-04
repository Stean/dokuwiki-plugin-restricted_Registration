<?php
/**
 * Options for the restrictedregistration plugin
 *
 * @author Stean <stean@gmx.org>
 */


$meta['ezmlm-binary']       = array('string');
$meta['mailinglist-subdir'] = array('string');

$conf['fallback_action']    = array('multichoice','_choices' => array('disallow','allow'));
$conf['prevent_change']     = array('onoff');