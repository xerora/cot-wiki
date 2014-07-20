<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

$m = cot_import('m', 'G', 'ALP');

if(in_array($m, array('edit', 'diff', 'history')))
{
	require_once cot_incfile('wiki', 'plug', $m);
}
else
{
	cot_die_message(404, TRUE);
}