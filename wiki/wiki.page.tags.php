<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
[END_COT_EXT]
==================== */

$rev = cot_import('rev', 'G', 'INT');

if(!empty($rev) && is_int($rev))
{
	require_once cot_incfile('wiki', 'plug');
	$wiki_revision = $db->query("SELECT * FROM {$db->wiki_revisions} WHERE rev_id=?", $rev)->fetch();

	if(!$wiki_revision)
	{
		cot_die_message(404, TRUE);
	}

	$t->assign(array(
		'PAGE_TEXT' => cot_parse($wiki_revision['rev_text'], true, $wiki_revision['revision_parser'])
	));

}