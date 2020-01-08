<?php
/* Smarty version 3.1.33, created on 2020-01-07 12:50:53
  from 'C:\xampp\htdocs\app\application\views\_menu\uitzender.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e14709d3e4c85_57367469',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6875c8ee2efaba09033f2950665db81464878bd2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\_menu\\uitzender.tpl',
      1 => 1578254257,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e14709d3e4c85_57367469 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="navbar navbar-expand-lg navbar-light navbar-sticky">
	<!-- hidden buttons for mobile view -->
	<div class="text-left d-lg-none">
		<!-- left side bar -->
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3 mr-2"></i>Zijmenu
		</button>
	</div>
	<div class="text-right d-lg-none">
		<!-- main menu -->
		<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-navigation">
			<i class="icon-unfold mr-2"></i>
			Menu
		</button>
	</div>

	<div class="navbar-collapse collapse" id="navbar-navigation">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a href="dashboard/uitzender" class="navbar-nav-link">
					<i class="icon-home4 mr-2"></i>
					Dashboard
				</a>
			</li>

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					<i class="icon-users mr-2"></i>
					CRM
				</a>

				<div class="dropdown-menu">
					<a href="crm/inleners" class="dropdown-item">
						<i class="icon-user-tie"></i>Inleners
					</a>
					<a href="crm/werknemers" class="dropdown-item">
						<i class="icon-user"></i>Werknemers
					</a>
				</div>
			</li>

			<li class="nav-item">
				<a href="facturenoverzicht/uitzender" class="navbar-nav-link">
					<i class="mi-euro-symbol mr-2" style="font-weight: bold"></i>
					Facturen & Marge
				</a>
			</li>


			<li class="nav-item">
				<a href="ureninvoer" class="navbar-nav-link">
					<i class="mi-timer mr-2" style="font-weight: bold"></i>
					Ureninvoer
				</a>
			</li>

					</ul>

	</div>
</div>


<?php }
}
