{extends file='../../layout.tpl'}
{block "title"}Gebruikers{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Gebruikers{/block}
{assign "datatable" "true"}

{block "content"}

    {if $usertype == 'werkgever'}{include file='instellingen/werkgever/_sidebar.tpl' active='users'}{/if}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xxl-6 col-xl-8 col-lg-12">

                    {if isset($msg)}{$msg}{/if}

					<!-- Basic card -->
					<div class="card">

						<div class="card-body">

							<form method="post" action="">

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Nieuwe gebruiker</legend>

                                    {*settings*}
                                    {assign "label_lg" "3"}
                                    {assign "div_xl" "8"}
                                    {assign "div_md" "8"}

									<input value="{$smarty.get.id|default:''}" name="id" type="hidden" class="form-control" readonly>

									<div class="form-group row">
										<label class="font-weight-bold col-lg-{$label_lg} col-form-label">Gebruiker voor*:</label>
										<div class="col-xl-{$div_xl} col-md-{$div_md}">
											<input value="{$user.user_type}" name="user_type" type="text" class="form-control" readonly>
										</div>
									</div>

										<!-- email -->
                                    {if isset($formdata.username)}
                                        {assign "field" "username"}
										<div class="form-group row">
											<label class="font-weight-bold col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												*:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{if $formdata.$field.value != ''}{$formdata.$field.value}{else}{$user.email|default:''}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off" required>
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}
	                                                    <br/>
                                                    {/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- naam -->
                                    {if isset($formdata.naam)}
                                        {assign "field" "naam"}
										<div class="form-group row">
											<label class="font-weight-bold col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}* :</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
                                                {if !isset($user_data.naam) || !is_array($user_data.naam)}
													<input value="{if $formdata.$field.value != ''}{$formdata.$field.value}{else}{$user_data.naam|default:''}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {else}
													<select name="{$field}" class="form-control">
                                                        {if !isset($formdata.$field.list.empty)}
															<option value=""></option>
                                                        {/if}
                                                        {foreach $user_data.naam as $naam}
															<option {if $formdata.$field.value == $naam}selected=""{/if} value="{$naam}">{$naam}</option>
                                                        {/foreach}
													</select>
                                                {/if}
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}
	                                                    <br/>
                                                    {/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- Admin -->
                                    {if isset($formdata.admin)}
                                        {assign "field" "admin"}
										<div class="form-group row">
											<label class="font-weight-bold col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}*:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value != '' && $formdata.$field.value == $option@key}checked=""{/if} required>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<button type="submit" name="set" class="btn btn-success">
										<i class="icon-add mr-1"></i>Toevoegen
									</button>
									<a href="instellingen/{$usertype}/users" class="btn btn-outline-danger">
										<i class="icon-cross mr-1"></i>
										Annuleren
									</a>

								</fieldset>

							</form>

						</div>
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}