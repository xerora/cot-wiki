<?php defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('page', 'module');

if(!cot::$db->fieldExists(cot::$db->structure, 'structure_wiki_enabled'))
{
	cot_extrafield_add(cot::$db->structure, 'wiki_enabled', 'radio', '<label><input type="radio" name="{$name}" value="{$value}"{$checked} /> {$title}</label>', '1,0', '0', false, 'HTML');
}
if(!cot::$db->fieldExists(cot::$db->structure, 'structure_wiki_subcats'))
{
	cot_extrafield_add(cot::$db->structure, 'wiki_subcats', 'radio', '<label><input type="radio" name="{$name}" value="{$value}"{$checked} /> {$title}</label>', '1,0', '0', false, 'HTML');
}