<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.done
[END_COT_EXT]
==================== */

if($id && cot::$structure['page'][$rpage['page_cat']]['wiki_enabled'])
{
	require_once cot_incfile('wiki', 'plug');

	$wiki_revision = wiki_revision_add(array(
		'rev_text' => $rpage['page_text'],
		'rev_parser' => $rpage['page_parser'],
	));

	wiki_history_add(array(
		'history_page_id' => (int)$id,
		'history_revision' => $wiki_revision,
		'history_author' => cot::$usr['id']
	));
}