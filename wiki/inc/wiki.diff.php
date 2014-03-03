<?php defined('COT_CODE') or die('Wrong URL');

$request_method = $_SERVER['REQUEST_METHOD'] == 'POST' ? 'P' : 'G';

$diff1 = cot_import('diff1', $request_method, 'INT');
$diff2 = cot_import('diff2', $request_method, 'INT');

require_once cot_incfile('page', 'module');

$diffs_rows = $db->query("SELECT r.*,h.* FROM {$db->wiki_revisions} AS r ".
	"LEFT JOIN {$db->wiki_history} AS h ON r.rev_id=h.history_revision ".
	"WHERE r.rev_id=? OR r.rev_id=? LIMIT 2", array($diff1, $diff2))->fetchAll();

$page = $db->query("SELECT page_title,page_cat FROM {$db->pages} WHERE page_id=?", $diffs_rows[0]['history_page_id'])->fetch();

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('page', $page['page_cat']);
cot_block($usr['isadmin']);

if(!$diffs_rows || count($diffs_rows) !== 2)
{
	cot_die_message(404, true);

}

$t = new XTemplate(cot_tplfile('wiki.difference', 'plug'));

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
				$t->parse('MAIN.DIFF.BLOCKS.DEFAULT');
			}
		}

		$t->parse('MAIN.DIFF.BLOCKS');
	}
	$t->parse('MAIN.DIFF');
}

$diff1_timestamp = strtotime($row_diff1['history_added']);
$diff2_timestamp = strtotime($row_diff2['history_added']);

$t->assign(array(
	'DIFF1_DATE' => wiki_datetime($diffs_rows[0]['history_added']),
	'DIFF1_AUTHOR' => htmlspecialchars($diff1['history_author']),
	'DIFF1_URL_EDIT' => cot_url('wiki', 'm=edit&rev='.$row_diff1['rev_id']),
	'DIFF2_DATE' => wiki_datetime($diffs_rows[1]['history_added']),
	'DIFF2_AUTHOR' => htmlspecialchars($diff2['history_author']),
	'DIFF2_URL_EDIT' => cot_url('wiki', 'm=edit&rev='.$row_diff2['rev_id']),
	'DIFF_TITLE' => htmlspecialchars($page['page_title']),
));
