<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.tags
[END_COT_EXT]
==================== */

require_once cot_incfile('wiki', 'plug');

$wiki_enabled = $structure['page'][$pag['page_cat']]['wiki_enabled'] ? true : false;

if($wiki_enabled)
{

	if(cot_plugin_active('i18n'))
	{
		require_once cot_incfile('i18n', 'plug');
		if(cot_i18n_enabled($cat))
		{
		}
	}

	$wiki_history = wiki_history_list($id);
	$wiki_history_count = count($wiki_history);
	$wiki_has_history = !empty($wiki_history) ? true : false;

	$wiki_current_rev = $db->query("SELECT history_revision FROM {$db->wiki_history} WHERE history_page_id=? ORDER BY history_added DESC LIMIT 1", $id)->fetchColumn();
	$wiki_previous_rev = '';
	$wiki_history_row_count = 0;

	foreach($wiki_history as $history)
	{
		$wiki_history_row_count++;
		if($wiki_history_row_count === 2)
		{
			$wiki_previous_rev = $history['history_revision'];
		}
		$t->assign(wiki_history_tags($history, 'WIKI_HISTORY_ROW_')
			+
			array(
				'WIKI_HISTORY_ROW_IS_CURRENT' => $wiki_current_rev == $history['history_revision'] ? true : false,
				'WIKI_HISTORY_ROW_COMPARE_WITH' => $wiki_history_count > 1 ? cot_checkbox('', 'diffs[]', '', '', $history['history_revision'], '') : '&nbsp;',
				'WIKI_HISTORY_ROW_URL_COMPARE_CURRENT' => cot_url('wiki', 'm=diff&diff2='.$wiki_current_rev.'&diff1='.$history['history_revision']),
			));
		$t->parse('MAIN.WIKI_HISTORY_ROW');
	}

	$t->assign(array(
		'WIKI_HISTORY_ACTION' => cot_url('wiki', 'm=diff&id='.$id)
	));
}
else
{
	$wiki_history = false;
	$wiki_has_history = false;
}
