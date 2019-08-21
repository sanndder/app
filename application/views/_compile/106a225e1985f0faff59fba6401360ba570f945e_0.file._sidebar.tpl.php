<?php
/* Smarty version 3.1.33, created on 2019-08-07 14:46:24
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\_sidebar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4ac820c8d3f9_82309840',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '106a225e1985f0faff59fba6401360ba570f945e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\_sidebar.tpl',
      1 => 1565179502,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4ac820c8d3f9_82309840 (Smarty_Internal_Template $_smarty_tpl) {
?>	<!-- Main sidebar -->
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

								<div class="card-header bg-transparent p-0">
					<div class="d-flex justify-content-between sidebar-buttons">

												<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 1) {?>
						<a href="crm/uitzenders/dossier/<?php echo $_smarty_tpl->tpl_vars['method']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->prev['id'];?>
" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->prev['id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->prev['bedrijfsnaam'];?>
">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/uitzenders" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar uitzenders
						</a>
						<a href="crm/uitzenders/dossier/<?php echo $_smarty_tpl->tpl_vars['method']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->next['id'];?>
" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->next['id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->next['bedrijfsnaam'];?>
">
							<i class="icon-arrow-right13"></i>
						</a>

												<?php } else { ?>
							<a href="crm/uitzenders" class="btn border-0 w-100 text-warning">
								<i class="icon-cross mr-1"></i>
								Annuleren
							</a>
						<?php }?>

					</div>
				</div>

				<!-- Main navigation -->
				<div class="card-body p-0">

					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- li Overzicht -->
						<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 1) {?>
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'overzicht') {?>active<?php }?>">
									<span>
										<i class="icon-home5 mr-2"></i>Overzicht
									</span>
								</a>
							</li>
						<?php }?>

						<!-- li Contactpersonen, verplaatsen naar einde lijst wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete != 1) {?>order-4<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->factuurgegevens_complete != NULL) {?>href="crm/uitzenders/dossier/contactpersonen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->factuurgegevens_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'contactpersonen') {?>active<?php }?>">
																		<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->contactpersoon_complete == NULL) {?>
										<i class="icon-checkbox-unchecked2 mr-2"></i>
									<?php } else { ?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 0) {?>
											<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
										<?php } else { ?>
																						<i class="icon-address-book3 mr-2"></i>
										<?php }?>
									<?php }?>
								Contactpersonen
							</a>
						</li>

						<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 1) {?>
							<!-- li Notities -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/notities/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'notities') {?>active<?php }?>">
									<i class="icon-pencil mr-2"></i>Notities
								</a>
							</li>

							<!-- li Documenten -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/documenten/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'documenten') {?>active<?php }?>">
									<i class="icon-file-text2 mr-2"></i>Documenten
								</a>
							</li>

							<!-- li Facturen -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/facturen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'facturen') {?>active<?php }?>">
									<i class="icon-coin-euro mr-2"></i>Facturen
								</a>
							</li>

							<!-- li Inleners -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/inleners/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'inleners') {?>active<?php }?>">
									<i class="icon-user-tie mr-2"></i>Inleners
								</a>
							</li>

							<!-- li Werknemers -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/werknemers/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'werknemers') {?>active<?php }?>">
									<i class="icon-user mr-2"></i>Werknemers
								</a>
							</li>

							<!-- Header Instellingen -->
							<li class="nav-item-header">Instellingen</li>


							<!-- li Algemene instellingen -->
							<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete != 1) {?>order-1<?php }?>">
								<a href="crm/uitzenders/dossier/algemeneinstellingen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'algemeneinstellingen') {?>active<?php }?>">
																		<i class="icon-cog mr-2"></i>
									Algemene instellingen
								</a>
							</li>
						<?php }?>


						<!-- li Bedrijfsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete != 1) {?>order-1<?php }?>">
							<a href="crm/uitzenders/dossier/bedrijfsgegevens/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'bedrijfsgegevens') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 0) {?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Bedrijfsgegevens
							</a>
						</li>

						<!-- li Emailinstellingen, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete != 1) {?>order-2<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete != NULL) {?>href="crm/uitzenders/dossier/emailadressen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'emailadressen') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->emailadressen_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 0) {?>
									<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Emailinstellingen
							</a>
						</li>

						<!-- li Factuurgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete != 1) {?>order-3<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->emailadressen_complete != NULL) {?>href="crm/uitzenders/dossier/factuurgegevens/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['uitzender']->value->emailadressen_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'factuurgegevens') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->factuurgegevens_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->complete == 0) {?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Factuurgegevens
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
<?php }
}
