<!-- BEGIN: MAIN -->

	<div class="block">
		{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

		<form action="{WIKI_EDIT_ACTION}" method="post" name="wikieditform">
		<h2>Editing "{WIKI_EDIT_TITLE}" at {WIKI_EDIT_DATE}</h2>

		<div>
			{WIKI_EDIT_OLD}
		</div>

			<table class="cells">
				<tr>
					<td>
						{WIKI_EDIT_TEXT}
					</td>
				</tr>
				<tr>
					<td><button type="submit">Submit</button>
			</table>
		</form>
	</div>

<!-- END: MAIN -->