<!-- BEGIN: MAIN -->
	
		<div class="row-fluid">
		<div class="col-md-12">

		{FILE "{PHP.cfg.themes_dir}/admin/{PHP.cfg.admintheme}/warnings.tpl"}

			<div class="block">
				<h5>Add Block Rule</h5>
				<div class="wrapper">
					<form method="POST" action="{RULE_ADD_ACTION}">
					<table class="table table-bordered">
						<tr>
							<td style="width: 30%;">Group</td>
							<td>{RULE_ADD_GROUPS}</td>
						</tr>
						<tr>
							<td>Category</td>
							<td>{RULE_ADD_CATEGORY}</td>
						</tr>
						<tr>
							<td>And all sub-categories ?</td>
							<td>{RULE_ADD_SUBCATEGORIES}
						</tr>
							<tr>
								<td colspan="2" style="text-align: center;"><button type="submit">Add rule</button></td>
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
				<!-- BEGIN: BLOCKED_GROUPS -->

					<form method="POST" action="{ACTION}">
					<table class="table table-striped">
						<thead>
							<tr>
								<th style="text-align: center; width: 8%;"><input type="checkbox" onclick="$('.wchecked').prop('checked', $(this).prop('checked'));" /></th>
								<th style="text-align: left; width: 15%; font-weight: bold;">Group ID</th>
								<th style="text-align: left; font-weight: bold;">Group</th>
								<th style="text-align: left; font-weight: bold;">Category</th>
								<th style="width: 15%; font-weight: bold;">All Sub-categories</th>
							</tr>
						</thead>
						<tbody>
						<!-- BEGIN: ROWS -->
							<tr>
								<td style="text-align: center;">{CHECKBOX}</td>
								<td>{GROUP_ID}</td>
								<td>{GROUP_NAME}</td>
								<td>{BLOCKED_CATEGORY}</td>
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