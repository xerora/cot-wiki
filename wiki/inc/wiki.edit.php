<?php defined('COT_CODE') or die('Wrong URL');

$id = cot_import('id', 'G', 'INT');
$rev = cot_import('rev', 'G', 'INT');
$a = cot_import('a', 'G', 'ALP');

require_once cot_incfile('page', 'module');
require_once $cfg['system_dir'] . '/header.php';
$t = new XTemplate(cot_tplfile('wiki.edit', 'plug'));

if($id)
{
	$row = $db->query("SELECT page_text,page_parser,page_cat FROM {$db->pages} WHERE page_id=? LIMIT 1", $id)->fetch();
	$wiki_text = $row['page_text'];
	$wiki_action = '&id='.(int)$row['page_id'];
}
if($rev)
{
	$row = $db->query("SELECT r.*,h.*,p.* FROM {$db->wiki_revisions} AS r ".
		"LEFT JOIN {$db->wiki_history} AS h ON r.rev_id=h.history_revision ".
		"LEFT JOIN {$db->pages} AS p ON p.page_id=h.history_page_id ".
		"WHERE r.rev_id=? LIMIT 1", $rev)->fetch();
	$wiki_text = $row['rev_text'];
	$wiki_action = '&rev='.(int)$row['rev_id'];
	$id = $row['history_page_id'];

	$t->assign(array(
		'WIKI_EDIT_DATE' => wiki_datetime($row['history_added']),
	));
}

$sys['parser'] = $row['page_parser'];

if(!$row)
{
	cot_die_message(404, true);
}

if($a == 'update')
{
	$rpage = array(
		'page_text' => cot_import('rwikitext', 'P', 'HTM')
	);

	$db->update($db->pages, array(
		'page_text' => $rpage['page_text']
		), "page_id=?", $id);

	$wiki_revision = wiki_revision_add(array(
		'rev_text' => $rpage['page_text'],
		'rev_parser' => $row['page_parser']
	));

	wiki_history_add(array(
		'history_page_id' => $id,
		'history_revision' => $wiki_revision,
		'history_author' => $usr['id']
	));

	cot_redirect(cot_url('page', 'c='.$row['page_cat'].'&id='.$id, '', true));
}

$t->assign(array(
	'WIKI_EDIT_OLD' => cot_parse($row['page_text'], true, $sys['parser']),
	'WIKI_EDIT_ACTION' => cot_url('wiki', 'm=edit&a=update'.$wiki_action),
	'WIKI_EDIT_TEXT' => cot_textarea('rwikitext', $wiki_text, 24, 120, '', 'input_textarea_editor'),
	'WIKI_EDIT_TITLE' => htmlspecialchars($row['page_title']),
));

$t->parse()->out();

require_once $cfg['system_dir'] . '/footer.php';