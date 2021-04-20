{extends file='layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="template/global_assets/js/plugins/extensions/jquery_ui/full.min.js"></script>
	<script src="recources/js/api/bing.js?{$time}"></script>



	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xl-12">

					<!-- msg -->
                    {if isset($msg)}
						<div class="row">
							<div class="col-xl-10">
                                {$msg}
							</div><!-- /col -->
						</div>
						<!-- /row -->
                    {/if}

					<!-- Default tabs -->
					<div class="card">
						<div class="card-body">

							<form method="get">

								<input type="text" class="form-control" data-bing="location" />

							</form>

						</div>
					</div>
					<!-- /default tabs -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div><!-- /main content -->

	<table style="font-family: Arial; font-size: 12px">
		<tr>
			<td style="vertical-align: text-top">
				<span style="font-size: 14px; font-weight: bold">Sander Meijering</span><br />
				Technisch Directeur

			</td>
			<td style="vertical-align: text-top">
				085-4855450 | 06-26951065<br />
				sander@flexx-groep.nl<br /><br />
				Rijnkade 34<br />
				6811HA Arnhem
			</td>
		</tr>
	</table>

{/block}