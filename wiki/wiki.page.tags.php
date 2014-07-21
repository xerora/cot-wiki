<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
[END_COT_EXT]
==================== */

$rev = cot_import('rev', 'G', 'INT');

require_once cot_incfile('wiki', 'plug');

if(!empty($rev) && is_int($rev) && $usr['isadmin'])
{
	$wiki_revision = $db->query("SELECT * FROM {$db->wiki_revisions} WHERE rev_id=?", $rev)->fetch();

	if(!$wiki_revision)
	{
		cot_die_message(404, TRUE);
	}

	$t->assign(array(
		'PAGE_TEXT' => cot_parse($wiki_revision['rev_text'], true, $wiki_revision['revision_parser'])
	));
}

if($usr['auth_write'] && !$usr['isadmin'] && wiki_category_enabled($pag['page_cat']))
{
	$wiki_edit_url = cot_url('wiki', 'm=edit&id='.$pag['page_id']);
	$t->assign(array(
		'WIKI_EDIT_URL' => $wiki_edit_url,
	));
	$t->parse('MAIN.WIKI_WRITE');
}

if($usr['auth_write'] && wiki_category_enabled($pag['page_cat']))
{
	$t->assign(array(
		'WIKI_HISTORY_URL' => cot_url('wiki', 'm=history&cat='.$c.'&id='.$id),
	));
	$t->parse('MAIN.WIKI_ENABLED');
}