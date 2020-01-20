{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "select2" "true"}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='cao'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-10">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">Kvk Informatie</legend>
								<table class="sbi table table-responsive">
									<thead>
										<tr>
											<th>SBI Code</th>
											<th>Bedrijfsactiviteiten</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
								<script>
                                    {literal}
									$.get("crm/inleners/ajax/sbi/{/literal}{$inlener->kvknr}{literal}", function(data){
										$.each(data.sbi, function(key, sbi){
											$('.sbi tbody').append('<tr><td>' + sbi.sbi_code + '</td><td>' + sbi.sbi_descrition + '</td></tr>')
										});
									}, "json");
                                    {/literal}
								</script>
							</fieldset>

							<form method="post" action="">
								<fieldset class="mb-4">
									<legend class="text-uppercase font-size-sm font-weight-bold">CAO toevoegen</legend>

                                    {* uitzender mag niet zelf kiezen voro geen cao *}
                                    {if $user_type == 'werkgever'}
										<div class="form-check mb-4">
											<label class="form-check-label">
												<input name="no_cao" value="1" type="checkbox" class="form-input-styled-info" {if $caos_inlener === false} checked{/if}>
												Inlener valt niet onder een CAO
											</label>
											<i class="icon-spinner2 spinner text-primary mr-1" style="display: none; margin-left: -27px"></i>
										</div>
										<script>
                                            {literal}
											$(document).ready(function(){
												$('[name="no_cao"]').on('change', function(){
													if( $(this).prop('checked') )
														$('.select-cao').hide().find('select').removeAttr('required');
													else
														$('.select-cao').show().find('select').attr('required', 'true');
												});
											});
										</script>
                                    {/literal}
                                    {/if}

									<table class="select-cao" {if $caos_inlener === false}style="display: none" {/if}>
										<tr>
											<td class="pr-2" style="width: 500px">
												<select {if $caos_inlener !== false} required{/if} name="cao_id" class="form-control select-search">
													<option value="">Selecteer een CAO</option>
                                                    {foreach $caos as $cao}
														<option value="{$cao.id}">{$cao.name} (start: {$cao.duration_start|date_format: '%d-%m-%Y'})</option>
                                                    {/foreach}
												</select>
											</td>
										</tr>
									</table>

									<button type="submit" name="set" value="add_cao_to_inlener" class="btn btn-success btn-sm mt-3">
										<i class="icon-check mr-1"></i>Wijzigingen opslaan
									</button>
								</fieldset>
							</form>

							{if is_array($caos_inlener)}
							<fieldset class="mb-4">
								<legend class="text-uppercase font-size-sm font-weight-bold">CAO overzicht</legend>

                                {foreach $caos_inlener as $cao}
									<fieldset class="mb-0 mt-0">
										<legend class="text-uppercase font-size-sm pl-2 mb-1 font-weight-bold" style="background-color: #EEE;">
                                            {$cao.cao_name}
											{if $user_type == 'werkgever'}
												<a href="{$base_url}/crm/inleners/dossier/cao/{$inlener->inlener_id}?tab=tab-cao&delcao={$cao.cao_id_intern}" class="float-right pr-3" style="color: red" data-popup="tooltip" data-placement="top" data-title="COA verwijderen">
													<i class="icon-trash"></i>
												</a>
                                            {/if}
										</legend>
									</fieldset>
                                {/foreach}

							</fieldset>
                            {/if}

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}