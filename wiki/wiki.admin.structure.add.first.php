<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.structure.add.first
[END_COT_EXT]
==================== */

if($n == 'page')
{
	$wiki_parent_path = explode('.', $rstructure['structure_path']);
	unset($wiki_parent_path[count($wiki_parent_path)-1]);
	$wiki_parent_path = implode('.', $wiki_parent_path);

	if(!empty($wiki_parent_path))
	{
		$wiki_parent_code = $db->query("SELECT structure_code FROM $db_structure WHERE structure_path=?", $wiki_parent_path)->fetchColumn();
		if($wiki_parent_code && $structure[$n][$wiki_parent_code]['wiki_subcats'])
		{
			$rstructure['structure_wiki_enabled'] = 1;
		}
	}

}