<?php defined('COT_CODE') or die('Wrong URL');

define('WIKI_HISTORY_LIMIT_DEFAULT', 50);

require_once cot_incfile('forms');

$id = cot_import('id', 'G', 'INT');
$cat = cot_import('cat', 'G', 'TXT');
$history_row_limit = (int)$cfg['plugin']['wiki']['history_row_limit'];
$history_row_limit = $history_row_limit ? $history_row_limit : WIKI_HISTORY_LIMIT_DEFAULT;

if(!wiki_category_enabled($cat))
{
	cot_die_message(403, true);
}

$rows_query = $db->query("SELECT h.*,u.user_name,u.user_id FROM $db_wiki_history AS h ".
	"LEFT JOIN ".$db_users." AS u ON h.history_author=u.user_id ".
	"WHERE h.history_page_id=? ORDER BY history_added DESC LIMIT ".$history_row_limit, $id);

$history_count = $rows_query->rowCount();
$rows = $rows_query->fetchAll();

require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_tplfile('wiki.history', 'plug'));

foreach($rows as $row)
{
	$t->assign(
		wiki_history_tags($row, 'HISTORY_')
		+
		array(
			'HISTORY_COMPARE_WITH' => $history_count > 1 ? cot_checkbox('', 'diffs[]', '', '', $history['history_revision'], '') : '&nbsp;',
		)
	);
	$t->parse('MAIN.ROWS');
}

$t->parse()->out();
cot_display_messages($t);
require_once $cfg['system_dir'] . '/footer.php';