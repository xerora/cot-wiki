<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.structure.first
[END_COT_EXT]
==================== */

if($n != 'page')
{
	unset($cot_extrafields[$db_structure]['wiki_enabled'], $cot_extrafields[$db_structure]['wiki_subcats']);
}
