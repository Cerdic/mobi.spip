<?php
/**
 * Plugin mobiSPIP
 * 
 * Distribue sous licence GPL 
 * (c) 2010 cedric
 * Date: 10/12/10 - 22:10
 *
 */

# Selecteur de version mobile
# si &var_mobile=1 dans l'url, switcher sur la version mobile
# si &var_mobile=0 dans l'url, switcher sur la version normale
if (!is_null($mobile=_request('var_mobile'))){
	include_spip('inc/cookie');
	spip_setcookie('mobispip',$_COOKIE['mobispip'] = (($mobile=='oui' OR intval($mobile))?'mobile':'normal'));
}
// si pas de cookie, on tente une autodetection
if (!isset($_COOKIE['mobispip'])){
	include_spip("detect/mobile");
	$mobile = (MobileDetect::getInstance()->isMobile() && !MobileDetect::getInstance()->isIpad());
	include_spip('inc/cookie');
	spip_setcookie('mobispip',$_COOKIE['mobispip'] = ($mobile?'mobile':'normal'));
}

if (!defined('_MOBISPIP'))
	define('_MOBISPIP',$_COOKIE['mobispip']=='mobile' AND !test_espace_prive());

if (_MOBISPIP) {
	_chemin(_DIR_PLUGIN_MOBISPIP.'mobile');
  $GLOBALS['marqueur'].="mobispip:";
	$GLOBALS['z_blocs'] = array('content','header','footer','head','head_js');
}

function mobispip_affichage_final($texte){
	if (_MOBISPIP){
		if (strpos($texte,'spip-admin-bloc')!==false){
			$texte = str_replace("spip-admin-bloc","mobispip-admin-bloc",$texte);
			$texte = preg_replace(",spip-admin-bouton([^>]*)>,","mobispip-admin-bouton$1 rel=\"external\" data-theme=\"e\">",$texte);
		}
	}
	elseif (strpos($texte,'mobispiplink')===false
	  AND $p=strpos($texte,'</body>')){
		$texte = substr_replace($texte,recuperer_fond('inc-mobispip-link'),$p,0);
	}
  return $texte;
}
# debug
define('_NO_CACHE',1);