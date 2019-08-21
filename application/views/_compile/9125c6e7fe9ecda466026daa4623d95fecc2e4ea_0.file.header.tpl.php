<?php
/* Smarty version 3.1.33, created on 2019-08-20 13:33:10
  from 'C:\xampp\htdocs\app\application\views\_page\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5bda760ec0c2_01933844',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9125c6e7fe9ecda466026daa4623d95fecc2e4ea' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\_page\\header.tpl',
      1 => 1566300787,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5bda760ec0c2_01933844 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand wmin-0 mr-5">
		<img src="template/global_assets/images/logo_light.png" alt="">
	</div>

	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="icon-tree5"></i>
		</button>
	</div>

	<div class="collapse navbar-collapse" id="navbar-mobile">

		<ul class="navbar-nav ml-auto">

			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
					<i class="icon-user mr-3 icon-2x"></i>
					<span><?php echo $_smarty_tpl->tpl_vars['user_name']->value;?>
</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
					<a href="mijnaccount/index" class="dropdown-item"><i class="icon-cog5"></i> Mijn account</a>
					<a href="login/index?logout" class="dropdown-item"><i class="icon-switch2"></i> Uitloggen</a>
				</div>
			</li>

		</ul>
	</div>

</div><?php }
}
