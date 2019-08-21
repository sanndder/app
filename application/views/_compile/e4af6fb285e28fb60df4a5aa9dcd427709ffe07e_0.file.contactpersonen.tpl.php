<?php
/* Smarty version 3.1.33, created on 2019-08-15 16:56:36
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\modals\contactpersonen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5572a43fcf81_69496878',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e4af6fb285e28fb60df4a5aa9dcd427709ffe07e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\modals\\contactpersonen.tpl',
      1 => 1565880995,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5572a43fcf81_69496878 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- load custom javascript -->
<?php echo '<script'; ?>
 src="recources/js/modals/contactpersonen.js" type="text/javascript"><?php echo '</script'; ?>
>

<!-- Contactpersonen form -->
<div id="modal_set_contact" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Contactpersoon <span class="var-action"></span></h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="ajax-wait mt-4 mb-4" style="text-align: center"><i class="icon-spinner2 spinner mr-1"></i>Gegevens
				worden geladen....
			</div>

			<div class="modal-body" style="display: none">

				<input type="hidden" name="contact_id">

				<form>

					<datepicker :language="nl" :bootstrap-styling="true"></datepicker>

					<!-- aanhef -->
					<div class="form-group row input-aanhef mb-3">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9 col-opions">

							<div class="form-check radio-template" style="display: none">
								<label class="form-check-label">
									<span class="">
										<input type="radio" class="" name="">
									</span>
								</label>
							</div>

							<span class="form-text text-danger"></span>
						</div>
					</div>


					<!-- voorletters -->
					<div class="form-group row input-voorletters mb-1">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- voornaam -->
					<div class="form-group row input-voornaam mb-1">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- tussenvoegsel -->
					<div class="form-group row input-tussenvoegsel mb-1">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-2">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- achternaam -->
					<div class="form-group row input-achternaam mb-3">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- functie -->
					<div class="form-group row input-functie mb-1">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- afdeling -->
					<div class="form-group row input-afdeling mb-3">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- telefoon -->
					<div class="form-group row input-telefoon mb-1">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- email -->
					<div class="form-group row input-email mb-3">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

					<!-- opmerking -->
					<div class="form-group row input-opmerking mb-3">
						<label class="col-form-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="text" name="" class="form-control">
							<span class="form-text text-danger"></span>
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer" style="display: none">
				<button type="button" onclick="setContact(this, 'uitzender', <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
 )" class="btn bg-primary">
					<i class="icon-checkmark2 mr-1"></i>Opslaan
				</button>
				<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
			</div>

		</div>
	</div>
</div>
<!-- /horizontal form modal --><?php }
}
