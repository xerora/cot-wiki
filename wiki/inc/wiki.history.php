<?php defined('COT_CODE') or die('Wrong URL');

define('WIKI_HISTORY_LIMIT_DEFAULT', 50);

require_once cot_incfile('forms');

$id = cot_import('id', 'G', 'INT');
$cat = cot_import('cat', 'G', 'TXT');
$history_row_limit = (int)$cfg['plugin']['wiki']['history_row_limit'];
$history_row_limit = $history_row_limit ? $history_row_limit : WIKI_HISTORY_LIMIT_DEFAULT;

list($pg, $d, $durl) = cot_import_pagenav('d', $cfg['plugin']['wiki']['history_row_limit']);

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('page', $cat);
cot_block($usr['auth_read']);

if(!wiki_category_enabled($cat))
{
	cot_die_message(403, true);
}

$rows_query = $db->query("SELECT h.*,u.user_name,u.user_id FROM $db_wiki_history AS h ".
	"LEFT JOIN ".$db_users." AS u ON h.history_author=u.user_id ".
	"WHERE h.history_page_id=? ORDER BY history_added DESC LIMIT ".(int)$d.", ".$history_row_limit, $id);

$history_count = $rows_query->rowCount();

if(!$history_count)
{
	cot_die_message(404, true);
}

$rows = $rows_query->fetchAll();
$rows_query->closeCursor();

if(cot_plugin_active('i18n'))
{
	require_once cot_incfile('i18n', 'plug');
	$locales = cot_i18n_list_page_locales($id);
}

$history_total_count = (int)$db->query("SELECT COUNT(*) FROM $db_wiki_history WHERE history_page_id=?", $id)->fetchColumn();

$common_url = '&cat='.$cat.'&id='.$id.'&d='.$durl;

$out['subtitle'] = $L['wiki_revision_history'];
require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_tplfile('wiki.history', 'plug'));

$history_order = 0;

foreach($rows as $row)
{
	$history_order++;
	$t->assign(
		wiki_history_tags($row, 'HISTORY_ROW_')
		+
		array(
			'HISTORY_ROW_COMPARE_WITH' => $history_count > 1 ? cot_checkbox('', 'diffs[]', '', '', $row['history_revision'], '') : '&nbsp;',
			'HISTORY_ROW_ORDER' => $history_order,
		)
	);
	$t->parse('MAIN.ROWS');
}

$pagenav = cot_pagenav('wiki', 'm=history'.$common_url, $d, $history_total_count, $cfg['plugin']['wiki']['history_row_limit'], 'd');

if(is_array($locales) && !empty($locales))
{
	foreach($locales as $locale)
	{
		$t->assign(array(
			'HISTORY_LOCALE_NAME' => htmlspecialchars($i18n_locales[$locale]),
			'HISTORY_LOCALE_ALIAS' => htmlspecialchars($locale),
		));
		$t->assign('MAIN.HISTORY_LOCALES');
	}
}

$t->assign(array(
	'HISTORY_COMPARE_ACTION' => cot_url('wiki', 'm=diff&cat='.$cat.'&id='.$id),
	'HISTORY_TOTAL_COUNT' => $history_count,
	'HISTORY_PAGENAV_MAIN' => $pagenav['main'],
	'HISTORY_PAGENAV_NEXT' => $pagenav['next'],
	'HISTORY_PAGENAV_PREV' => $pagenav['prev'],
	'HISTORY_PAGENAV_LAST' => $pagenav['last'],
	'HISTORY_PAGENAV_CURRENT' => $pagenav['current'],
	'HISTORY_PAGENAV_FIRSTLINK' => $pagenav['firstlink'],
	'HISTORY_PAGENAV_PREVLINK' => $pagenav['prevlink'],
	'HISTORY_PAGENAV_NEXTLINK' => $pagenav['nextlink'],
	'HISTORY_PAGENAV_LASTLINK' => $pagenav['lastlink'],
	'HISTORY_PAGENAV_TOTAL' => $pagenav['total'],
	'HISTORY_PAGENAV_ONPAGE' => $pagenav['onpage'],
	'HISTORY_PAGENAV_ENTRIES' => $pagenav['entries'],
));

cot_display_messages($t);
$t->parse()->out();
require_once $cfg['system_dir'] . '/footer.php';