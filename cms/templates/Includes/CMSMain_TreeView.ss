<div class="cms-content-toolbar">
	<% include CMSPagesController_ContentToolbar %>
</div>

<div class="ss-dialog cms-page-add-form-dialog cms-dialog-content" id="cms-page-add-form" title="<% _t('CMSMain.AddNew', 'Add new page') %>">
	$AddForm
</div>

<button href="$LinkPages" class="cms-tree-expand-trigger cms-panel-link ss-button" data-icon="pencil">
	<% _t('CMSMain.EditTree', 'Edit Tree') %>
</button>

<div class="center">
	<% if TreeIsFiltered %>
	<div class="cms-tree-filtered">
		<strong><% _t('CMSMain.TreeFiltered', 'Filtered tree.') %></strong>
		<a href="$LinkPages" class="cms-panel-link">
			<% _t('CMSMain.TreeFilteredClear', 'Clear filter') %>
		</a>
	</div>
	<% end_if %>

	<div class="cms-tree" data-url-tree="$Link(getsubtree)" data-url-savetreenode="$Link(savetreenode)" data-hints="$SiteTreeHints">
		$SiteTreeAsUL
	</div>
</div>
