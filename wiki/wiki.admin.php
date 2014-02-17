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

$common_url = 'm=other&p=wiki';

if($a == 'addrule')
{
	cot_check_xp();
	$rrule = array(
		'perm_groupid' => cot_import('rulegroup', 'P', 'INT'),
		'perm_cat' => cot_import('rcat', 'P', 'TXT'),
		'perm_catsub' => cot_import('rallsubcats', 'P', 'BOL'),
	);

	$db->insert($db->wiki_perms_group, $rrule);
	cot_message('wiki_msg_added_rule');
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

$blocked_groups_rows = $db->query("SELECT * FROM {$db->wiki_perms_group}")->fetchAll();
if(!empty($blocked_groups_rows))
{
	foreach($blocked_groups_rows as $blocked_group)
	{
		$t->assign(array(
			'GROUP_ID' => (int)$blocked_group['perm_groupid'],
			'CHECKBOX' => cot_checkbox('', 'rchecked[]', '', array('class' => 'wchecked'), $blocked_group['perm_id']),
			'GROUP_NAME' => htmlspecialchars($cot_groups[$blocked_group['perm_groupid']]['name']),
			'BLOCKED_CATEGORY' => htmlspecialchars($structure['page'][$blocked_group['perm_cat']]['title']),
			'BLOCKED_SUBCATEGORIES' => (bool)$blocked_group['perm_catsub'] === true ? $L['Yes'] : $L['No'],
		));
		$t->parse('MAIN.BLOCKED_GROUPS.ROWS');
	}
	$t->assign(array(
		'ACTION' => cot_url('admin', $common_url.'&a=checked'),
	));
	$t->parse('MAIN.BLOCKED_GROUPS');
}
else
{
	$t->parse('MAIN.BLOCKED_GROUPS_EMPTY');
}

$t->assign(array(
	'RULE_ADD_ACTION' => cot_url('admin', $common_url.'&a=addrule'),
	'RULE_ADD_CATEGORY' => cot_selectbox_structure('page', '', 'rcat'), 
	'RULE_ADD_GROUPS' => wiki_groups_selectbox(),
	'RULE_ADD_SUBCATEGORIES' => cot_radiobox(0, 'rallsubcats', array(1,0), array($L['Yes'], $L['No'])),
));

cot_display_messages($t);
$plugin_body = $t->parse()->text();