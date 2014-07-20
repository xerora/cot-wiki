<!-- BEGIN: MAIN -->

	<div class="block">
		{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

		<form action="{WIKI_EDIT_ACTION}" method="post" name="wikieditform">
		<h2>Editing "{WIKI_EDIT_TITLE}" at {WIKI_EDIT_DATE}</h2>

		<div style="margin: 25px 0;">
			{WIKI_EDIT_OLD}
		</div>

			<table class="cells">
				<tr>
					<td>
						{WIKI_EDIT_TEXT}
					</td>
				</tr>
				<tr>
					<td><strong>{PHP.L.wiki_edit_message}</strong> &nbsp; {WIKI_EDIT_COMMENT}</td>
				</tr>
				<tr>
					<td style="text-align: center; padding: 10px 0;"><button type="submit">{PHP.L.Publish}</button>
					</td>
				</tr>
			</table>
		</form>
	</div>

<!-- END: MAIN -->