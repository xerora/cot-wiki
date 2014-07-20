<!-- BEGIN: MAIN -->

<div class="block">
	{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
	<h2>{PHP.L.wiki_history_title}</h2>
	<form method="post" action="{HISTORY_COMPARE_ACTION}">
	<button type="submit">{PHP.L.wiki_compare}</button>
	</form>
	<table class="cells">
		<!-- BEGIN: ROWS -->
		<tr>
			<td style="width: 5%; text-align: center;">{HISTORY_COMPARE_WITH}</td>
			<td style="width: 20%;">{HISTORY_AUTHOR_LINK}</td>
			<td style="width: 35%;">{HISTORY_COMMENT}</td>
			<td style="width: 20%;">{HISTORY_TIMEAGO}</td>
			<td style="width: 20%;"><a href="{HISTORY_URL_AT}">{HISTORY_ADDED}</a></td>
		</tr>
		<!-- END: ROWS -->
	</table>
</div>

<!-- END: MAIN -->