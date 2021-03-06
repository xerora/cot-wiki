<?php defined('COT_CODE') or die('Wrong URL');

$request_method = $_SERVER['REQUEST_METHOD'] == 'POST' ? 'P' : 'G';

$id = (int)cot_import('id', 'G', 'INT');
$cat = cot_import('cat', 'G', 'TXT');
$diff1 = cot_import('diff1', $request_method, 'INT');
$diff2 = cot_import('diff2', $request_method, 'INT');
$diffs = wiki_filter_diff_import(cot_import('diffs', $request_method, 'ARR'));
$d = cot_import('d', 'G', 'INT');

require_once cot_incfile('page', 'module');
$page = $db->query("SELECT page_title,page_cat FROM {$db->pages} WHERE page_id=?", $id)->fetch();

if(!$page)
{
	cot_die_message(404);
}

if(!empty($diffs))
{

	if(count($diffs) !== 2 || !is_int($diffs[0]) || !is_int($diffs[1]))
	{
		$diffs = null;
	}

	// Find most recent revision
	if($diffs[0] > $diffs[1])
	{
		$diff1 = $diffs[1];
		$diff2 = $diffs[0];
	}
	else
	{
		$diff1 = $diffs[0];
		$diff2 = $diffs[1];
	}
}

if(!isset($diffs) && (empty($diff1) && empty($diff2)) )
{
	cot_error('wiki_history_invalid_parameters');
	cot_redirect(cot_url('wiki', 'm=history&cat='.$cat.'&id='.$id, '', true));	
}

$diffs_rows = $db->query("SELECT r.*,h.* FROM {$db->wiki_revisions} AS r ".
	"LEFT JOIN {$db->wiki_history} AS h ON r.rev_id=h.history_revision ".
	"WHERE r.rev_id=? OR r.rev_id=? LIMIT 2", array($diff1, $diff2))->fetchAll();

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('page', $page['page_cat']);
cot_block($usr['auth_write']);

if(!$diffs_rows || count($diffs_rows) !== 2)
{
	cot_die_message(404, true);

}

$t = new XTemplate(cot_tplfile('wiki.diff', 'plug'));

$cache_identifier = $diff1.'_'.$diff2;
$cache && $changes = $cache->disk->get($cache_identifier, WIKI_CACHE_DIFF_REALM);

if(!$changes)
{
	// Parse the difference text to make sure markup such as MarkDown can be easily stripped with strip_tags
	$diff1_text = explode("\n", strip_tags(cot_parse($diffs_rows[0]['rev_text'], true, $diffs_rows[0]['rev_parser'])));
	$diff2_text = explode("\n", strip_tags(cot_parse($diffs_rows[1]['rev_text'], true, $diffs_rows[1]['rev_parser'])));

	require_once $cfg['plugins_dir'] .'/wiki/lib/phpdiff/Diff.php';
	require_once $cfg['plugins_dir'] .'/wiki/lib/phpdiff/Diff/Renderer/Html/Array.php';

	$diff = new Diff($diff1_text, $diff2_text);
	$diff_renderer = new Diff_Renderer_Html_Array();
	$changes = $diff->render($diff_renderer);
	$cache && $cache->disk->store($cache_identifier, $changes, WIKI_CACHE_DIFF_REALM);
}

foreach($changes as $i => $blocks)
{
	if($i > 0)
	{
		$t->parse('MAIN.DIFF.SKIPPED');
	}

	foreach($blocks as $change)
	{
		if($change['tag'] == 'equal')
		{
			foreach($change['base']['lines'] as $no => $line)
			{
					$diff_from_line = $change['base']['offset'] + $no +1;
					$diff_to_line = $change['changed']['offset'] + $no + 1;
					$t->assign(array(
						'DIFF_FROM_LINE' => $diff_from_line,
						'DIFF_TO_LINE' => $diff_to_line,
						'DIFF_LINE' => $line,
					));
					$t->parse('MAIN.DIFF.BLOCKS.UNCHANGED');
			}
		}
		elseif($change['tag'] == 'insert')
		{
			foreach($change['changed']['lines'] as $no => $line)
			{
				$diff_to_line = $change['changed']['offset'] + $no + 1;
				$t->assign(array(
					'DIFF_LINE' => $line,
					'DIFF_TO_LINE' => $diff_to_line,
				));
				$t->parse('MAIN.DIFF.BLOCKS.INSERT');
			}
		}
		elseif($change['tag'] == 'delete')
		{
			foreach($change['base']['lines'] as $no => $line)
			{
				$diff_from_line = $change['base']['offset'] + $no + 1;
				$t->assign(array(
					'DIFF_LINE' => $line,
					'DIFF_FROM_LINE' => $diff_from_line
				));
				$t->parse('MAIN.DIFF.BLOCKS.DELETE');
			}
		}
		elseif($change['tag'] == 'replace')
		{
			if(count($change['base']['lines']) >= count($change['changed']['lines']))
			{
				foreach($change['base']['lines'] as $no => $line)
				{
					$diff_from_line = $change['base']['offset'] + $no + 1;

					if(!isset($change['changed']['lines'][$no]))
					{
						$diff_to_line = '&nbsp;';
						$diff_changed_line = '&nbsp;';
					}
					else
					{
						$diff_to_line = $change['base']['offset'] + $no + 1;
						$diff_changed_line = $change['changed']['lines'][$no];
					}
					$t->assign(array(
						'DIFF_LINE' => $line,
						'DIFF_TO_LINE' => $diff_to_line,
						'DIFF_FROM_LINE' => $diff_from_line,
						'DIFF_CHANGED_LINE' => $diff_changed_line,
					));
					$t->parse('MAIN.DIFF.BLOCKS.REPLACE');
				}
			}
			else
			{
				foreach($change['changed']['lines'] as $no => $changedLine)
				{
					if(!isset($change['base']['lines'][$no]))
					{
						$diff_from_line = '&nbsp;';
						$line = '&nbsp;';
					}
					else
					{
						$diff_from_line = $change['base']['offset'] + $no + 1;
						$line = $change['base']['lines'][$no];
					}
					$diff_to_line = $change['changed']['offset'] + $no + 1;

					$t->assign(array(
						'DIFF_TO_LINE' => $diff_to_line,
						'DIFF_FROM_LINE' => $diff_from_line,
						'DIFF_CHANGED_LINE' => $changedLine,
						'DIFF_LINE' => $line
					));
					$t->parse('MAIN.DIFF.BLOCKS.CHANGED');
				}
			}
		}

		$t->parse('MAIN.DIFF.BLOCKS');
	}
	$t->parse('MAIN.DIFF');
}

$diff1_timestamp = strtotime($row_diff1['history_added']);
$diff2_timestamp = strtotime($row_diff2['history_added']);

$diff_edit_url = cot_url('wiki', 'm=edit&rev='.$diffs_rows[0]['history_page_id']);
if($usr['isadmin'])
{
	$diff_edit_url = cot_url('page', 'm=edit&id='.$id);
}

$t->assign(
	wiki_diff_tags($diffs_rows[0], 'DIFF1_')
	+
	wiki_diff_tags($diffs_rows[1], 'DIFF2_')
	+
	array(
		'DIFF_TITLE' => htmlspecialchars($page['page_title']),
		'DIFF_EDIT_URL' => $diff_edit_url,
	)
);
