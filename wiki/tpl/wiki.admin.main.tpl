<!-- BEGIN: MAIN -->
	
		<div class="row-fluid">
		<div class="col-md-12">

		{FILE "{PHP.cfg.themes_dir}/admin/{PHP.cfg.admintheme}/warnings.tpl"}

			<div class="block">
				<h5>{PHP.L.wiki_add_rule_block}</h5>
				<div class="wrapper">
					<form method="POST" action="{RULE_ADD_ACTION}">
					<table class="table table-bordered">
						<tr>
							<td style="width: 30%;">{PHP.L.Group}</td>
							<td>{RULE_ADD_GROUPS}</td>
						</tr>
						<tr>
							<td>{PHP.L.Category}</td>
							<td>{RULE_ADD_CATEGORY}</td>
						</tr>
						<tr>
							<td>{PHP.L.wiki_all_subcats} ?</td>
							<td>{RULE_ADD_SUBCATEGORIES}
						</tr>
							<tr>
								<td colspan="2" style="text-align: center;"><button type="submit">{PHP.L.wiki_add_rule}</button></td>
							</tr>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-12">
			<div class="block">
				<h5>Block Rules</h5>
				<div class="wrapper">

				<!-- BEGIN: FILTER -->
					<h4>{PHP.L.Filter}:</h4>
					<form method="POST" action="{FILTER_ACTION}">
					<div style="margin-top: 15px; margin-bottom: 15px;">
						<strong>{PHP.L.Group}:</strong>&nbsp; {FILTER_GROUP} &nbsp; &nbsp; <strong>{PHP.L.Category}:</strong>&nbsp; {FILTER_CATEGORY}
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-primary">{PHP.L.Filter}</button>
					</div>
					</form>

					<hr />
				<!-- END: FILTER -->

				<!-- BEGIN: BLOCKED_GROUPS -->

					<form method="POST" action="{ACTION}">
					<table class="table table-striped">
						<thead>
							<tr>
								<th style="text-align: center; width: 8%;"><input type="checkbox" onclick="$('.wchecked').prop('checked', $(this).prop('checked'));" /></th>
								<th style="text-align: left; font-weight: bold;">{PHP.L.Group}</th>
								<th style="text-align: left; font-weight: bold;">{PHP.L.Category}</th>
								<th style="width: 15%; font-weight: bold;">{PHP.L.wiki_all_subcats}</th>
							</tr>
						</thead>
						<tbody>
						<!-- BEGIN: ROWS -->
							<tr>
								<td style="text-align: center;">{CHECKBOX}</td>
								<td>{GROUP_NAME}</td>
								<td>{BLOCKED_CATEGORY_PATH}</td>
								<td style="text-align: center;">{BLOCKED_SUBCATEGORIES}</td>
							</tr>
						<!-- END: ROWS -->
						</tbody>
					</table>

					<div style="margin-bottom: 15px; float: right;">
						<span style="vertical-align: middle; font-size: 14px;">With selected:</span> &nbsp;
						<button type="submit" name="raction" value="1" class="btn btn-danger">Delete</button>
					</div>
					</form>
				<!-- END: BLOCKED_GROUPS -->

				<!-- BEGIN: BLOCKED_GROUPS_EMPTY -->
					<div style="margin-bottom: 20px;">
						{PHP.L.wiki_no_rules}
					</div>
				<!-- END: BLOCKED_GROUPS_EMPTY -->
				</div>
			</div>
		</div>
	</div>

<!-- END: MAIN -->