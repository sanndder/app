{extends file='../layout.tpl'}
{block "title"}Mijn account{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Mijn account{/block}
{assign "ckeditor" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Gegevens gebruiker</h5>
				</div>

				<div class="card-body">

					<form method="post" action="">

						{if isset($msg)}
							<div class="row">
								<div class="col-md-12">
									{$msg}
								</div><!-- /col -->
							</div><!-- /row -->
						{/if}

						<div class="row">
							<div class="col-xl-6 col-lg-12">

								<!-- Username/emailadres -->
								{if isset($formdata.username)}
									{assign "field" "username"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- bedrijfsnaam -->
								{if isset($formdata.naam)}
									{assign "field" "naam"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

							</div><!-- /col -->
						</div><!-- /row -->

						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success"><i class="icon-checkmark2 mr-1"></i>Opslaan</button>
							</div><!-- /col -->
						</div><!-- /row -->

					</form>

				</div><!-- /card body -->
			</div><!-- /basic card -->

			<!-- Wachtwoord -------------------------------------------------------------------------------------------------->

			<!-- Basic card -->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Wachtwoord wijzigen</h5>
				</div>

				<div class="card-body">

					<form method="post" action="">

						{if isset($msg_password)}
							<div class="row">
								<div class="col-md-12">
									{$msg_password}
								</div><!-- /col -->
							</div><!-- /row -->
						{/if}

						<div class="row">
							<div class="col-xl-6 col-lg-12">

								<!-- Oude wachtwoord -->
								<div class="form-group row">
									<label class="col-lg-3 col-form-label">Huidig wachtwoord:</label>
									<div class="col-xl-8 col-md-8">
										<input value="" name="password[old]" type="password" class="form-control" placeholder="" autocomplete="off">
									</div>
								</div>

								<!-- Oude wachtwoord -->
								<div class="form-group row mt-4">
									<label class="col-lg-3 col-form-label">Nieuw wachtwoord:</label>
									<div class="col-xl-8 col-md-8">
										<input value="" name="password[1]" type="password" class="form-control" placeholder="" autocomplete="off">
									</div>
								</div>

								<!-- Oude wachtwoord -->
								<div class="form-group row">
									<label class="col-lg-3 col-form-label">Herhaal wachtwoord:</label>
									<div class="col-xl-8 col-md-8">
										<input value="" name="password[2]" type="password" class="form-control" placeholder="" autocomplete="off">
									</div>
								</div>

							</div><!-- /col -->
						</div><!-- /row -->

						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="setpassword" class="btn btn-success"><i class="icon-checkmark2 mr-1"></i>Opslaan</button>
							</div><!-- /col -->
						</div><!-- /row -->

					</form>

				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}