<?php
/* Smarty version 3.1.33, created on 2020-01-08 13:47:19
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\_sidebar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e15cf579cab93_13598527',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '47d0d09214570426c5556866035ed4778504c6c9' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\_sidebar.tpl',
      1 => 1578171970,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e15cf579cab93_13598527 (Smarty_Internal_Template $_smarty_tpl) {
?>	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

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

												<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 1) {?>
						<a href="crm/inleners/dossier/<?php echo $_smarty_tpl->tpl_vars['method']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->prev['id'];?>
" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: <?php echo $_smarty_tpl->tpl_vars['inlener']->value->prev['id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->prev['bedrijfsnaam'];?>
">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/inleners" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar inleners
						</a>
						<a href="crm/inleners/dossier/<?php echo $_smarty_tpl->tpl_vars['method']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->next['id'];?>
" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: <?php echo $_smarty_tpl->tpl_vars['inlener']->value->next['id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->next['bedrijfsnaam'];?>
">
							<i class="icon-arrow-right13"></i>
						</a>

												<?php } else { ?>
							<a href="crm/inleners" class="btn border-0 w-100 text-warning">
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
						<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 1) {?>
							<li class="nav-item">
								<a href="crm/inleners/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'overzicht') {?>active<?php }?>">
									<span>
										<i class="icon-home5 mr-2"></i>Overzicht
									</span>
								</a>
							</li>
						<?php }?>

						<!-- li Contactpersonen, verplaatsen naar einde lijst wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-5<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['inlener']->value->factuurgegevens_complete != NULL) {?>href="crm/inleners/dossier/contactpersonen/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['inlener']->value->factuurgegevens_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'contactpersonen') {?>active<?php }?>">
																		<?php if ($_smarty_tpl->tpl_vars['inlener']->value->contactpersoon_complete == NULL) {?>
										<i class="icon-checkbox-unchecked2 mr-2"></i>
									<?php } else { ?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 0) {?>
											<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
										<?php } else { ?>
																						<i class="icon-address-book3 mr-2"></i>
										<?php }?>
									<?php }?>
								Contactpersonen
							</a>
						</li>

						<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 1) {?>
							<!-- li Notities -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/notities/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'notities') {?>active<?php }?>">
									<i class="icon-pencil mr-2"></i>Notities
								</a>
							</li>

							<!-- li Documenten -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/documenten/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'documenten') {?>active<?php }?>">
									<i class="icon-file-text2 mr-2"></i>Documenten
								</a>
							</li>

							<!-- li Krediet -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/kredietoverzicht/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'kredietoverzicht') {?>active<?php }?>">
									<i class="icon-stats-dots mr-2"></i>Kredietoverzicht
								</a>
							</li>

							<!-- li Facturen -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/facturen/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'facturen') {?>active<?php }?>">
									<i class="icon-coin-euro mr-2"></i>Facturen
								</a>
							</li>

							<!-- li Werknemers -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/werknemers/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'werknemers') {?>active<?php }?>">
									<i class="icon-user mr-2"></i>Werknemers
								</a>
							</li>

							<!-- Header Instellingen -->
							<li class="nav-item-header">Instellingen</li>


							<!-- li Algemene instellingen -->
							<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-1<?php }?>">
								<a href="crm/inleners/dossier/algemeneinstellingen/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'algemeneinstellingen') {?>active<?php }?>">
																		<i class="icon-cog mr-2"></i>
									Algemeen
								</a>
							</li>
						<?php }?>


						<!-- li Bedrijfsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-2<?php }?>">
							<a href="crm/inleners/dossier/bedrijfsgegevens/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'bedrijfsgegevens') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 0) {?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Bedrijfsgegevens
							</a>
						</li>

						<!-- li Emailinstellingen, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-3<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete != NULL) {?>href="crm/inleners/dossier/emailadressen/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'emailadressen') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['inlener']->value->emailadressen_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 0) {?>
									<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Emailadressen
							</a>
						</li>

						<!-- li Factuurgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-4<?php }?>">
							<a <?php if ($_smarty_tpl->tpl_vars['inlener']->value->emailadressen_complete != NULL) {?>href="crm/inleners/dossier/factuurgegevens/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
"<?php }?> class="nav-link <?php if ($_smarty_tpl->tpl_vars['inlener']->value->emailadressen_complete == NULL) {?>nav-link-disabled<?php }?> <?php if ($_smarty_tpl->tpl_vars['active']->value == 'factuurgegevens') {?>active<?php }?>">
																<?php if ($_smarty_tpl->tpl_vars['inlener']->value->factuurgegevens_complete == NULL) {?>
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								<?php } else { ?>
									<?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 0) {?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 0) {?><i class="icon-pencil7 mr-2"></i><?php }?>
										<?php if ($_smarty_tpl->tpl_vars['inlener']->value->bedrijfsgegevens_complete == 1) {?><i class="icon-checkbox-checked mr-2"></i><?php }?>
									<?php } else { ?>
																				<i class="icon-cog mr-2"></i>
									<?php }?>
								<?php }?>
								Factuurgegevens
							</a>
						</li>

                        <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete == 1) {?>
						<!-- li Verloningsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item <?php if ($_smarty_tpl->tpl_vars['inlener']->value->complete != 1) {?>order-5<?php }?>">
							<a href="crm/inleners/dossier/verloninginstellingen/<?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'verloninginstellingen') {?>active<?php }?>">
																<i class="icon-cog mr-2"></i>
								CAO & Verloning
							</a>
						</li>
						<?php }?>

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
