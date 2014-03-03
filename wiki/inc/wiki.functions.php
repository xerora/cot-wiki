<?php defined('COT_CODE') or die('Wrong URL');

define('WIKI_DEFAULT_FORMAT_DATETIME', 'F jS, Y H:i');
define('WIKI_CACHE_DIFF_REALM', 'wiki_difference');

cot::$db->registerTable('wiki_history');
cot::$db->registerTable('wiki_revisions');
cot::$db->registerTable('wiki_perms_group');

function wiki_history_tags($row, $prefix = 'HISTORY_')
{
	$added_timestamp = strtotime($row['history_added']);
	$author_name = htmlspecialchars($row['user_name']);
	return array(
		$prefix.'REVISION' => (int)$row['history_revision'],
		$prefix.'LANGUAGE' => htmlspecialchars($row['history_language']),
		$prefix.'TIMEAGO' => cot_build_timeago($added_timestamp),
		$prefix.'ADDED' => wiki_datetime($row['history_added']),
		$prefix.'ADDED_STAMP' => $added_timestamp,
		$prefix.'AUTHOR_ID' => (int)$row['history_id'],
		$prefix.'AUTHOR_NAME' => $author_name,
		$prefix.'AUTHOR_LINK' => cot_build_user($row['history_author'], $author_name),
		$prefix.'URL_DIFF' => cot_url('wiki', 'm=diff&id='.$row['history_page_id']),
		$prefix.'URL_AT' => cot_url('page', 'c='.$row['page_cat'].'&id='.$row['history_page_id'].'&rev='.$row['history_revision']),
		$prefix.'URL_EDIT' => cot_url('wiki', '&m=edit&rev='.(int)$row['history_revision']),
	);
}

function wiki_history_list($id, $lang = '')
{
	if(empty($lang))
	{
		$lang = cot::$cfg['defaultlang'];
	}
	if($lang == 'all')
	{
		$lang = null;
	}

	if(!empty($lang))
	{
		$lang = cot::$db->prep($lang);
		$sql_lang = "AND history_language='{$lang}'";
	}

	return cot::$db->query("SELECT h.*,u.user_name,p.page_cat FROM ".cot::$db->wiki_history." AS h ".
		"LEFT JOIN ".cot::$db->users." AS u ON u.user_id=h.history_author ".
		"LEFT JOIN ".cot::$db->pages." AS p ON p.page_id=h.history_page_id ".
		"WHERE history_page_id=? {$sql_lang} ORDER BY h.history_added DESC", $id)->fetchAll();
}

function wiki_revision_add($data)
{
	cot::$db->insert(cot::$db->wiki_revisions, $data);
	return cot::$db->lastInsertId();
}

function wiki_history_add($data)
{
	if(!isset($data['history_language']))
	{
		$data['history_language'] = cot::$cfg['defaultlang'];
	}
	if(!isset($data['history_added']))
	{
		$data['history_added'] = wiki_history_datetime();
	}
	if(!isset($data['history_ip']))
	{
		$data['history_ip'] = cot::$usr['ip'];
	}
	
	return cot::$db->insert(cot::$db->wiki_history, $data);
}

function wiki_block_group($group, $cat)
{
	$blocked = false;
	$path = explode('.', cot::$structure['page'][$cat]['path']);
	$path_preped = array();
	foreach($path as $path_category)
	{
		$path_preped[] = cot::$db->prep($path_category);
	}
	$perms = cot::$db->query("SELECT * FROM ".cot::$db->wiki_perms_group." WHERE perm_groupid=? AND perm_cat IN ('".implode("','", $path_preped)."')", $group)->fetchAll();
	$perms_count = count($perms);
	$selected_cat_perms = $perms[$perms_count-1];
	if($selected_cat_perms['perm_cat'] == $cat)
	{
		$blocked = true;
	}
	if(!$blocked && $perms_count > 1)
	{
		foreach($perms as $perm)
		{
			if($perm['perm_catsub'])
			{
				$blocked = true;
				break;
			}
		}
	}

	return $blocked;
}

function wiki_datetime($input = null)
{
	$now = cot::$sys['now'];
	if(isset($input))
	{
		$now = is_string($input) ? strtotime($input) : (int)$input;
	}
	return cot_date(WIKI_DEFAULT_FORMAT_DATETIME, $now);
}

function wiki_history_datetime()
{
	return cot_date('Y-m-d h:i:s', time(), false);
}

function wiki_categories_selectbox($inputname, $value = '', $prependempty = false)
{
	$output = cot_rc('wiki_select_open', array('name' => $inputname));
	if($prependempty)
	{
		$output .= cot_rc('wiki_select_option', array('name' => '----', 'value' => ''));
	}
	foreach(cot::$structure['page'] as $name => $data)
	{
		if($data['wiki_enabled'])
		{
			$output .= cot_rc('wiki_select_option',
				array(
					'name' => htmlspecialchars($data['tpath']),
					'value' => $name,
					'selected' => ($name == $value) ? 'selected="selected"' : '',
				)
			);
		}
	}
	$output .= cot_rc('wiki_select_close', array());
	return $output;
}

function wiki_category_enabled($cat)
{
	$struc = cot_structure_parents('page', $cat);
	if(cot::$structure['page'][$cat]['wiki_enabled'])
	{
		return true;
	}
	foreach($struc as $c)
	{
		if(cot::$structure['page'][$c]['wiki_subcats'])
		{
			return true;
		}
	}
	return false;
}

function wiki_groups_selectbox($inputname, $value = '', $prependempty = false)
{
	global $cot_groups;
	$output = cot_rc('wiki_select_open', array('name' => $inputname));
	if($prependempty)
	{
		$output .= cot_rc('wiki_select_option', array('name' => '----', 'value' => ''));
	}
	foreach($cot_groups as $group => $data)
	{
		$output .= cot_rc('wiki_select_option',
			array(
				'name' => htmlspecialchars($data['name']),
				'value' => (int)$group,
				'selected' => ($group == $value) ? 'selected="selected"' : '',
			)
		);
	}
	$output .= cot_rc('wiki_select_close', array());
	return $output;
}
