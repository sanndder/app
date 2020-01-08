<?php
/* Smarty version 3.1.33, created on 2020-01-07 23:00:17
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\_sidebar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e14ff718dbb84_38182247',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '99df09bcec7b96b7e15631d89ce8aad78d8f32ab' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\_sidebar.tpl',
      1 => 1578434413,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e14ff718dbb84_38182247 (Smarty_Internal_Template $_smarty_tpl) {
?>	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Instellingen menu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">
			<div class="card card-sidebar-mobile">

				<!-- Main navigation -->
				<div class="card-body p-0">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Algemene instellingen</div>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/users" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'users') {?>active<?php }?>">
								<span>Gebruikers</span>
							</a>
						</li>
                        <?php if ($_smarty_tpl->tpl_vars['werkgever_type']->value == 'uitzenden') {?>
						<li class="nav-item">
							<a href="instellingen/werkgever/minimumloon" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'minimumloon') {?>active<?php }?>">
								<span>Minimumloon</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/feestdagen" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'feestdagen') {?>active<?php }?>">
								<span>Feestdagen</span>
							</a>
						</li>
						<?php }?>
						<li class="nav-item">
							<a href="instellingen/werkgever/urentypes" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'urentypes') {?>active<?php }?>">
								<span>Urentypes</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/vergoedingen" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'vergoedingen') {?>active<?php }?>">
								<span>Vergoedingen</span>
							</a>
						</li>
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Instellingen entiteiten</div>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/bedrijfsgegevens" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'bedrijfsgegevens') {?>active<?php }?>">
								<span>Bedrijfsgegevens</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/av" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'av') {?>active<?php }?>">
								<span>Algemene voorwaarden</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/documentenoverzicht" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'documenten') {?>active<?php }?>">
								<span>Documenten</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/bankrekeningen" class="nav-link <?php if ($_smarty_tpl->tpl_vars['active']->value == 'bankrekeningen') {?>active<?php }?>">
								<span>Bankrekeningen</span>
							</a>
						</li>
						<!-- /main -->
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
