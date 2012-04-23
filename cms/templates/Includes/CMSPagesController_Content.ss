<div id="pages-controller-cms-content" class="cms-content center cms-tabset $BaseCSSClasses" data-layout-type="border">

	<div class="cms-content-header north">
		<div>
			<h2>
				<% include CMSBreadcrumbs %>
			</h2>
		
			<div class="cms-content-header-tabs">
				<ul>
					<li>
						<a href="#cms-content-treeview" class="content-treeview cms-panel-link" data-href="$LinkTreeView"><% _t('CMSPagesController.TreeView', 'Tree View') %></a>
					</li>
					<li>
						<a href="#cms-content-listview" class="content-listview cms-panel-link"  data-href="$LinkListView"><% _t('CMSPagesController.ListView', 'List View') %></a>
					</li>
					<!--
					<li>
						<a href="#cms-content-galleryview" class="content-galleryview"><% _t('CMSPagesController.GalleryView', 'Gallery View') %></a>
					</li>
					-->
				</ul>
			</div>
		</div>
	</div>

	$Tools

	<div class="cms-content-fields center ui-widget-content cms-panel-padded">
		
		<div class="cms-content-view cms-panel-deferred" id="cms-content-treeview" data-url="$LinkTreeView">
			<%-- Lazy-loaded via ajax --%>
		</div>
	
		<div class="cms-content-view cms-panel-deferred" id="cms-content-listview" data-url="$LinkListView">
			<%-- Lazy-loaded via ajax --%>
		</div>
		<!--
		<div id="cms-content-galleryview">
			<i>Not implemented yet</i>
		</div>
		-->
		
	</div>
	
</div>