{extends file='layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/cao/cao.js?{$time}"></script>
	<script src="recources/js/werknemer/plaatsing.js?{$time}"></script>


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

								<div class="row">
									<div class="col-xl-2">CAO</div>
									<div class="col-xl-6">

										<select required name="cao_id" class="form-control select-search">
											<option value="">Selecteer een CAO</option>
                                            {if $caos !== NULL}
                                                {foreach $caos as $cao}
													<option {if isset($smarty.get.cao_id) && $smarty.get.cao_id == $cao@key} selected{/if} value="{$cao@key}">{$cao.name}</option>
                                                {/foreach}
                                            {/if}
										</select>
									</div>
								</div>

								{if isset($loontabellen) && $loontabellen !== NULL}
								<div class="row mt-4">
									<div class="col-xl-2">Loontabel</div>
									<div class="col-xl-6">

										<select required name="tabel_id" class="form-control select-search">
											<option value="">Selecteer een loontabel</option>
                                            {if $loontabellen !== NULL}
                                                {foreach $loontabellen as $tabel}
													<option {if isset($smarty.get.tabel_id) && $smarty.get.tabel_id == $tabel.salary_table_id} selected{/if} value="{$tabel.salary_table_id}">{$tabel.short_name} - {$tabel.description}</option>
                                                {/foreach}
                                            {/if}
										</select>
									</div>
								</div>
                                {/if}

                                {if isset($jobs) && $jobs !== NULL}
									<div class="row mt-4">
										<div class="col-xl-2">Functie</div>
										<div class="col-xl-6">

											<select required name="functie_id" class="form-control select-search">
												<option value="">Selecteer een functie</option>
                                                {if $jobs !== NULL}
                                                    {foreach $jobs as $job}
														<option {if isset($smarty.get.functie_id) && $smarty.get.functie_id == $job@key} selected{/if} value="{$job@key}">{$job.name}</option>
                                                    {/foreach}
                                                {/if}
											</select>
										</div>
									</div>
                                {/if}

                                {if isset($schalen) && $schalen !== NULL}
									<div class="row mt-4">
										<div class="col-xl-2">Schaal</div>
										<div class="col-xl-6">

											<select required name="schaal_id" class="form-control select-search">
												<option value="">Selecteer een schaal</option>
                                                {if $jobs !== NULL}
                                                    {foreach $schalen as $schaal}
														<option {if isset($smarty.get.schaal_id) && $smarty.get.schaal_id == $schaal@key} selected{/if} value="{$schaal@key}">{$schaal}</option>
                                                    {/foreach}
                                                {/if}
											</select>
										</div>
									</div>
                                {/if}

                                {if isset($periodieken) && $periodieken !== NULL}
									<div class="row mt-4">
										<div class="col-xl-2">Ervaring in jaren</div>
										<div class="col-xl-6">

											<select required name="periodiek_id" class="form-control select-search">
												<option value="">Selecteer aantal jaren ervaring</option>
                                                {if $periodieken !== NULL}
                                                    {foreach $periodieken as $p}
														<option {if isset($smarty.get.periodiek_id) && $smarty.get.periodiek_id == $p@key} selected{/if} value="{$p@key}">{$p|number_format:0}</option>
                                                    {/foreach}
                                                {/if}
											</select>
										</div>
									</div>
                                {/if}

								{if isset($uurloon)}
									<div class="row mt-4">
										<div class="col-xl-2">Uurloon</div>
										<div class="col-xl-6">
											{$uurloon|number_format:2:',':'.'}
										</div>
									</div>
								{/if}


								<button type="submit" name="verder" class="btn btn-success mt-5">
									Verder
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