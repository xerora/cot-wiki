<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=i18n.page.translate.tags
[END_COT_EXT]
==================== */

$rev = cot_import('rev', 'G', 'INT');

if($rev)
{
	$revision = $db->query("SELECT * FROM $db_revisions WHERE rev_id=?", $rev)->fetch();
	if($revision)
	{
		$t->assign(array(
			'I18N_PAGE_TEXT' => cot_parse($revision['rev_text'], $revision['rev_parser']),
			'I18N_IPAGE_TEXT' => cot_textarea('translate_text', $revision['rev_text'], 32, 80, '', 'input_textarea_editor'),
		));
	}
}

