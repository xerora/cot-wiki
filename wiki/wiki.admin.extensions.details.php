<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.extensions.details
[END_COT_EXT]
==================== */

/** 
* Hide structure button/link since the plugin uses the 'admin.structure.first' hook
* in a way not expected
*/

if($code == 'wiki')
{
	$t->assign(array(
		'ADMIN_EXTENSIONS_JUMPTO_URL_STRUCT' => null,
	));
}