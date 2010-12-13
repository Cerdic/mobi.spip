<?php
/**
 * Plugin mobiSPIP
 * 
 * Distribue sous licence GPL 
 * (c) 2010 cedric
 * Date: 10/12/10 - 22:10
 *
 */

define('_VERSION_MOBILE',!test_espace_prive());
define('_NO_CACHE',1);

if (defined('_VERSION_MOBILE') AND _VERSION_MOBILE) {
	_chemin(_DIR_PLUGIN_MOBISPIP.'mobile');
  $GLOBALS['marqueur'].="mobispip:";
	$GLOBALS['z_blocs'] = array('content','header','footer','head','head_js');
}