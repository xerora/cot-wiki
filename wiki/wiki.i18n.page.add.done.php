<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=i18n.page.add.done
[END_COT_EXT]
==================== */

require_once cot_incfile('wiki', 'plug');

$wiki_revision = wiki_revision_add(array(
	'rev_text' => $pag_i18n['ipage_text'],
	'rev_parser' => $pag_i18n['page_parser'],
));

if($wiki_revision)
{
	wiki_history_add(array(
		'history_page_id' => $id,
		'history_language' => $i18n_locale,
		'history_revision' => $wiki_revision,
		'history_author' => cot::$usr['id'],
	));
}