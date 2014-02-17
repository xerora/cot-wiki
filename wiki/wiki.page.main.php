<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=page.main
[END_COT_EXT]
==================== */

require_once cot_incfile('wiki', 'plug');

$wiki_edit_allowed = wiki_block_group($usr['maingrp']);
$wiki_edit_url = cot_url('wiki', 'm=edit&id='.$row['page_id']);

if($wiki_edit_allowed)
{
	$t->assign(array(
		'WIKI_EDIT_URL' => $wiki_edit_url,
	));
	$t->parse('MAIN.WIKI_EDIT');
}
