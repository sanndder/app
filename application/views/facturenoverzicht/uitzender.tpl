{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Facturen & Marge{/block}
{assign "ckeditor" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="card">


				<div class="card-header header-elements-inline">
					<h5 class="card-title">Recente facturen</h5>
				</div>

				<div class="table-responsive">
					<div class="p-4 font-italic">Geen facturen gevonden</div>
				</div>


			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}