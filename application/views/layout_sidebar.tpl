{if $user_type == 'werkgever'}
	<div class="sidebar-right">
		<div class="sidebar-header">
		</div>
		<div class="sidebar-content-custom">

			<ul class="nav nav-tabs nav-justified" style="margin-left: -1px">
				<li class="nav-item">
					<a href="#sidebar-task-tab" class="nav-link rounded-top active" data-toggle="tab">
						<i class="icon-add-to-list mr-1"></i>Taak toevoegen
					</a>
				</li>
				<li class="nav-item">
					<a href="#sidebar-task-gegevens" class="nav-link rounded-top" data-toggle="tab">
						<i class="icon-file-text2 mr-1"></i>Bedrijfsgegevens
					</a>
				</li>
			</ul>

			<div class="tab-content">
				{*
				<!--------------------------------------------------------------------------------------------
				|| taak toevoegen
				--------------------------------------------------------------------------------------------->
				<div class="tab-pane fade active show" id="sidebar-task-tab">

					<div class="card-body">

						<form>

							<div class="form-group">
								<label class="font-weight-bold">Taak titel *</label>
								<input name="taak-tile" autocomplete="off" value="" type="text" class="form-control"/>
							</div>

							<div class="form-group">
								<label class="font-weight-bold">Categorie</label>
								<select name="taak-categorie_id" class="form-control" style="width:100%">
									{foreach $taak_categorien as $categorie}
										<option value="{$categorie@key}">{$categorie}</option>
									{/foreach}
								</select>
							</div>


							<div class="form-group">
								<label class="">Taak omschrijving</label>
								<textarea name="taak-omschrijving" class="form-control"></textarea>
							</div>

							<div class="input-group">
								<div class="form-group">
									<label class="">Einddatum</label>
									<input name="taak-datum-eind" autocomplete="off" value="" type="text" class="form-control pickadate"/>
								</div>
							</div>


							<div class="form-group">
								<label class="">Tijdvak</label>

								<div class="input-group">
									<select class="form-control" name="taak-tijdvak">
										<option value=""></option>
										<option value="w">week</option>
										<option value="4w">4 weken</option>
										<option value="m">maand</option>
									</select>
									<input name="taak-jaar" autocomplete="off" value="" type="text" class="form-control" placeholder="jaar" />
									<input name="taak-periode" autocomplete="off" value="" type="text" class="form-control" placeholder="periode" />
								</div>
							</div>

							<div class="form-group">
								<label class="">Uitzender</label>
								<select name="taak-uitzender_id" class="form-control select-search" style="width:100%">
									<option value="">-- Geen uitzender --</option>
								</select>
							</div>

							<div class="form-group">
								<label class="">Inlener</label>
								<select name="taak-inlener_id" class="form-control select-search" style="width:100%">
									<option value="">-- Geen inlener --</option>
								</select>
							</div>

							<div class="form-group">
								<label class="">Werknemer</label>
								<select name="taak-werknemer_id" class="form-control select-search" style="width:100%">
									<option value="">-- Geen werknemer --</option>
								</select>
							</div>

							<div class="form-group">

								<button class="btn btn-sm btn-success">
									<i class="icon-check mr-1"></i>Taak opslaan
								</button>

								<button class="btn btn-sm btn-outline-danger">
									<i class="icon-cross2 mr-1"></i>Alles wissen
								</button>
							</div>

						</form>
					</div>
*}
				</div>

				<!--------------------------------------------------------------------------------------------
				|| bedrijfsgegevens
				--------------------------------------------------------------------------------------------->
				<div class="tab-pane fade" id="sidebar-task-gegevens">

					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							NL96SNSB0821159593
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							NL860648400B01
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							hsmeijering@home.nl
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							76504069
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							Reitscheweg 37
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							5232BX 's-Hertogenbosch
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							Factris: NL14 INGB 0007 8661 77
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							Uitzenden: NL49 INGB 0007 2918 89
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							G-rekening: NL 93 INGB 0990 3336 20
						</div><!-- /col -->
					</div><!-- /row -->
					<div class="row ml-2">
						<div class="col-md-12 pl-2 pt-2">
							Bemiddeling: NL41INGB 0006 4341 65
						</div><!-- /col -->
					</div><!-- /row -->


				</div>

			</div>


		</div>
	</div>
{/if}
