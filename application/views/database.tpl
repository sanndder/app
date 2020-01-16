{extends file='layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {/block}

{block "content"}

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

							<form method="post" action="">

								<textarea style="width: 100%; height: 550px;" name="sql"></textarea>

								<button type="submit" name="go">
									Uitvoeren
								</button>
							</form>

						</div>
					</div>
					<!-- /default tabs -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}