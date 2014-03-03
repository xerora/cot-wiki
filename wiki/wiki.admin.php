<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

$a = cot_import('a', 'G', 'ALP');

require_once cot_incfile('wiki', 'plug');
require_once cot_incfile('wiki', 'plug', 'resources');
$t = new XTemplate(cot_tplfile('wiki.admin.main', 'plug'));

list($pg, $d, $durl) = cot_import_pagenav('d', $cfg['maxrowsperpage']);
$groupid = cot_import('groupid', $_SERVER['REQUEST_METHOD'], 'INT');
$cat = cot_import('cat', $_SERVER['REQUEST_METHOD'], 'TXT');

$common_url = 'm=other&p=wiki&groupid='.$groupid.'&cat='.$cat.'&pg='.$pg;
$where_sql = "";

if($a == 'addrule')
{
	cot_check_xp();
	$rule_conflict = false;
	$rrule = array(
		'perm_groupid' => cot_import('rulegroup', 'P', 'INT'),
		'perm_cat' => cot_import('rcat', 'P', 'TXT'),
		'perm_catsub' => cot_import('rallsubcats', 'P', 'BOL'),
	);
	$existing_group_rules = $db->query("SELECT perm_cat,perm_catsub FROM {$db->wiki_perms_group} WHERE perm_groupid=?", $rrule['perm_groupid']);
	foreach($existing_group_rules as $erule)
	{
		if($erule['perm_cat'] == $rrule['perm_cat'] || (bool)$erule['perm_catsub'] && in_array($rrule['perm_cat'], cot_structure_children('page', $erule['perm_cat'])))
		{
			$rule_conflict = true;
		}
	}

	if(!$rule_conflict)
	{
		$db->insert($db->wiki_perms_group, $rrule);
		cot_message('wiki_msg_added_rule');
	}
	else
	{
		cot_error('wiki_msg_rule_conflict');
	}
}
if($a == 'checked')
{
	cot_check_xp();
	$action = cot_import('raction', 'P', 'INT');
	$checked = cot_import('rchecked', 'P', 'ARR');
	$items = array();
	switch($aciton)
	{
		case 0:
			// delete
			foreach($checked as $item)
			{
				$item = (int)$item;
				if(!$item) continue;
				$items[] = $item;
			}
			if(!empty($items))
			{
				$deleted = $db->delete($db->wiki_perms_group, "perm_id IN ('".implode("','", $items)."')");
				cot_message(cot_rc('wiki_msg_mass_delete', array('count' => $deleted)));
			}
		break;
	}
}
if($a == 'filter')
{
	$filter_sql = array();

	if(!empty($groupid))
	{
		$filter_sql[] = "perm_groupid=".$groupid;
	}
	if(!empty($cat))
	{
		$filter_sql[] = "perm_cat=".$db->quote($cat);
	}

	$where_sql = implode(' AND ', $filter_sql);
}

if(!empty($where_sql))
{
	$where_sql = "WHERE ".$where_sql;
}

$totalrows = $db->query("SELECT COUNT(*) FROM {$db->wiki_perms_group} {$where_sql}")->fetchColumn();
$blocked_groups_rows = $db->query("SELECT * FROM {$db->wiki_perms_group} {$where_sql} LIMIT ".(int)$d.", ".(int)$cfg['maxrowsperpage'])->fetchAll();
if(!empty($blocked_groups_rows))
{
	foreach($blocked_groups_rows as $blocked_group)
	{
		$t->assign(array(
			'GROUP_ID' => (int)$blocked_group['perm_groupid'],
			'CHECKBOX' => cot_checkbox('', 'rchecked[]', '', array('class' => 'wchecked'), $blocked_group['perm_id']),
			'GROUP_NAME' => htmlspecialchars($cot_groups[$blocked_group['perm_groupid']]['name']),
			'BLOCKED_CATEGORY' => htmlspecialchars($structure['page'][$blocked_group['perm_cat']]['title']),
			'BLOCKED_CATEGORY_PATH' => htmlspecialchars($structure['page'][$blocked_group['perm_cat']]['tpath']),
			'BLOCKED_SUBCATEGORIES' => $blocked_group['perm_catsub'] ? $L['Yes'] : $L['No'],
			'ACTION' => cot_url('admin', $common_url.'&a=checked'),
		));
		$t->parse('MAIN.BLOCKED_GROUPS.ROWS');
	}
	$t->parse('MAIN.BLOCKED_GROUPS');
}
else
{
	$t->parse('MAIN.BLOCKED_GROUPS_EMPTY');
}

$t->assign(array(
	'FILTER_ACTION' => cot_url('admin', $common_url.'&a=filter'),
	'FILTER_GROUP' => wiki_groups_selectbox('groupid', $groupid, true),
	'FILTER_CATEGORY' => wiki_categories_selectbox('cat', $cat, true),
));

$t->parse('MAIN.FILTER');

$pagenav = cot_pagenav('admin', $common_url, $d, $totalrows, $cfg['maxrowsperpage']);

$t->assign(array(
	'RULE_ADD_ACTION' => cot_url('admin', $common_url.'&a=addrule'),
	'RULE_ADD_CATEGORY' => wiki_categories_selectbox('rcat'),
	'RULE_ADD_GROUPS' => wiki_groups_selectbox('rulegroup'),
	'RULE_ADD_SUBCATEGORIES' => cot_radiobox(0, 'rallsubcats', array(1,0), array($L['Yes'], $L['No'])),
	'RULE_PAGENAV_PAGES' => $pagenav['main'],
	'RULE_PAGENAV_PREV' => $pagenav['prev'],
	'RULE_PAGENAV_NEXT' => $pagenav['next'],
	'RULE_PAGENAV_CURRENT' => $pagenav['current'],
));

cot_display_messages($t);
$plugin_body = $t->parse()->text();