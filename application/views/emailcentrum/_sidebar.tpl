<!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-lg align-self-start">

	<!-- Sidebar mobile toggler -->
	<div class="sidebar-mobile-toggler text-center">
		<a href="#" class="sidebar-mobile-main-toggle">
			<i class="icon-arrow-left8"></i>
		</a>
		<span class="font-weight-semibold">Menu</span>
		<a href="#" class="sidebar-mobile-expand">
			<i class="icon-screen-full"></i>
			<i class="icon-screen-normal"></i>
		</a>
	</div>
	<!-- /sidebar mobile toggler -->


	<!-- Sidebar content -->
	<div class="sidebar-content">
		<div class="card card-sidebar-mobile">


			<!-- Main navigation -->
			<div class="card-body p-0">

				<ul class="nav nav-sidebar" data-nav-type="accordion">

					<!-- li header -->
					<li class="nav-item-header font-weight-bolder">
						<div class="text-uppercase font-size-xs line-height-xs">Weergave</div>
					</li>

					<!-- li wachtrij -->
					<li class="nav-item">
						<a href="emailcentrum" class="nav-link {if isset($folder) && $folder == ''}active{/if}">
							<i class="fa fa-hourglass-half" style="margin-right: 16px"></i>Wachtrij
						</a>
					</li>

					<!-- li wachtrij -->
					<li class="nav-item">
						<a href="emailcentrum/index/send" class="nav-link {if isset($folder) && $folder == 'send'}active{/if}">
							<i class="icon-drawer-out mr-2"></i>Verzonden
						</a>
					</li>

					<!-- li wachtrij -->
					<li class="nav-item">
						<a href="emailcentrum/index/trash" class="nav-link {if isset($folder) && $folder == 'trash'}active{/if}">
							<i class="icon-trash mr-2"></i>Verwijderd
						</a>
					</li>


				</ul>
			</div>
			<!-- /main navigation -->

		</div>
	</div>
	<!-- /sidebar content -->

</div>
<!-- /main sidebar  -->
