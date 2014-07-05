<!-- BEGIN: MAIN -->
<link href="{PHP.cfg.plugins_dir}/wiki/inc/wiki.css" type="text/css" rel="stylesheet" />

<div class="block">
	<h2>Difference between revisions of "{DIFF_TITLE}"</h2>

	<div style="margin: 10px 0; float: right;">
		<a style="font-size: 14px; font-weight: bold;" href="{DIFF_EDIT_URL}">{PHP.L.Edit}</a>
	</div>

	<table style="width: 100%; border-spacing: 10px; border-collapse: separate;">
		<thead>
			<tr>
				<th style="width: 50%;" colspan="2">Revision at {DIFF1_DATE}</th>
				<th style="width: 50%;" colspan="2">Revision at {DIFF2_DATE}</th>
			</tr>
		</thead>
	<!-- BEGIN: DIFF -->

		<!-- BEGIN: SKIPPED -->
			<tbody>
				<th>&hellip;</th><td>&nbsp;</td>
				<th>&hellip;</th><td>&nbsp;</td>
			</tbody>
		<!-- END: SKIPPED -->

		<!-- BEGIN: BLOCKS -->

		<tbody>
			<!-- BEGIN: UNCHANGED -->
				<tr>
					<th>{DIFF_FROM_LINE}</th>
					<td class="wiki-line-unchanged"><span>{DIFF_LINE}</span>&nbsp;</td>
					<th>{DIFF_TO_LINE}</th>
					<td class="wiki-line-unchanged"><span>{DIFF_LINE}</span>&nbsp;</td>
				</tr>
			<!-- END: UNCHANGED -->

			<!-- BEGIN: INSERT -->
				<tr>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
					<th>{DIFF_TO_LINE}</th>
					<td class="wiki-line-insert">{DIFF_LINE}</td>
				</tr>
			<!-- END: INSERT -->

			<!-- BEGIN: DELETE -->
				<tr>
					<th>{DIFF_FROM_LINE}</th>
					<td class="wiki-line-delete">{DIFF_LINE}</td>
					<th>&nbsp;</th>
					<td class="wiki-line-delete">&nbsp;</td>
				</tr>
			<!-- END: DELETE -->

			<!-- BEGIN: REPLACE -->
				<tr>
					<th>{DIFF_FROM_LINE}</th>
					<td class="wiki-line-replace">{DIFF_LINE}&nbsp;</td>
					<th>{DIFF_TO_LINE}</th>
					<td class="wiki-line-replace">{DIFF_CHANGED_LINE}</td>
				</tr>
			<!-- END: REPLACE -->

			<!-- BEGIN: CHANGED -->
				<tr>
					<th>{DIFF_FROM_LINE}</th>
					<td class="wiki-line-changed">{DIFF_LINE}&nbsp;</td>
					<th>{DIFF_TO_LINE}</th>
					<td class="wiki-line-changed">{DIFF_CHANGED_LINE}</td>
				</tr>
			<!-- END: CHANGED -->

			</tbody>
		<!-- END: BLOCKS -->

	<!-- END: DIFF -->

	</table>

</div>

<!-- END: MAIN -->