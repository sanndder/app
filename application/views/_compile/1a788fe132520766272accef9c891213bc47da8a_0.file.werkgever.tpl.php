<?php
/* Smarty version 3.1.33, created on 2019-12-04 15:36:27
  from 'C:\xampp\htdocs\app\application\views\_menu\werkgever.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de7c46b069748_69071788',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a788fe132520766272accef9c891213bc47da8a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\_menu\\werkgever.tpl',
      1 => 1575450421,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5de7c46b069748_69071788 (Smarty_Internal_Template $_smarty_tpl) {
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
				<a href="dashboard/werkgever" class="navbar-nav-link">
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
					<a href="crm/uitzenders" class="dropdown-item">
						<i class="icon-office"></i>Uitzenders
					</a>
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

			<li class="nav-item">
				<a href="instellingen/werkgever" class="navbar-nav-link">
					<i class="icon-cog mr-2"></i>
					Instellingen
				</a>
			</li>
		</ul>

		<ul class="navbar-nav ml-md-auto">
			<li class="nav-item">
				<span class="navbar-nav-link toggle-right-sidebar">
					<i class="icon-pencil mr-2"></i>Notitie
				</span>
			</li>
		</ul>

	</div>
</div>


<?php }
}
