<!-- BEGIN: MAIN -->

<div class="block">
	{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
	<h2>{PHP.L.wiki_history_title}</h2>

	<form method="post" action="{HISTORY_COMPARE_ACTION}">
	<div style="margin: 15px 0;">
			<button type="submit">{PHP.L.wiki_compare_revisions}</button>
	</div>

	<table class="cells">
		<!-- BEGIN: ROWS -->
		<tr>
			<td style="width: 5%; text-align: center;">{HISTORY_ROW_COMPARE_WITH}</td>
			<td style="width: 20%;"><a href="{HISTORY_ROW_URL_AT}">{HISTORY_ROW_ADDED}</a></td>
			<td style="width: 20%;">{HISTORY_ROW_TIMEAGO}</td>
			<td style="width: 20%;">{HISTORY_ROW_AUTHOR_LINK}</td>
			<td style="width: 35%;">{HISTORY_ROW_COMMENT}</td>
		</tr>
		<!-- END: ROWS -->
	</table>
	<div style="margin-top: 15px;">
		{HISTORY_PAGENAV_PREV} {HISTORY_PAGENAV_MAIN} {HISTORY_PAGENAV_NEXT}
	</div>
	</form>
</div>

<!-- END: MAIN -->