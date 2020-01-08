<?php
/* Smarty version 3.1.33, created on 2020-01-07 14:14:09
  from 'C:\xampp\htdocs\app\application\views\_page\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e1484216935d9_45359320',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9125c6e7fe9ecda466026daa4623d95fecc2e4ea' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\_page\\header.tpl',
      1 => 1578402848,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e1484216935d9_45359320 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand wmin-0 mr-5">
		<img src="template/global_assets/images/logo_light.png" alt="">
	</div>

		<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="icon-tree5"></i>
		</button>
	</div>

	<?php if (isset($_smarty_tpl->tpl_vars['user_accounts']->value) && is_array($_smarty_tpl->tpl_vars['user_accounts']->value) && count($_smarty_tpl->tpl_vars['user_accounts']->value) > 1) {?>
	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav">

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="true">
					<i class="icon-users2"></i>
					<span class="ml-2"><?php echo $_smarty_tpl->tpl_vars['user_accounts']->value[$_smarty_tpl->tpl_vars['account_id']->value]['name'];?>
</span>
					<i class="icon-arrow-down5"></i>
				</a>

				<div class="dropdown-menu dropdown-content wmin-md-350 ">
					<div class="dropdown-content-header pb-2">
						<span class="font-weight-semibold">Uw accounts</span>
					</div>

					<div class="dropdown-content-body p-0">
						<ul class="media-list">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['user_accounts']->value, 'a');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value) {
$__foreach_a_0_saved = $_smarty_tpl->tpl_vars['a'];
?>
							<li class="media font-size-lg dropdown-item p-3">
								<a href="<?php echo $_smarty_tpl->tpl_vars['current_url']->value;?>
?switchto=<?php echo $_smarty_tpl->tpl_vars['a']->key;?>
" class="w-100 h-100">
									<div class="d-flex justify-content-between w-100">
										<div><i class="icon-circle-right2 mr-2"></i><?php echo $_smarty_tpl->tpl_vars['a']->value['name'];?>
</div>
										<?php if ($_smarty_tpl->tpl_vars['account_id']->value == $_smarty_tpl->tpl_vars['a']->key) {?><div><span class="badge bg-success ml-md-3">Actief</span></div><?php }?>
									</div>
								</a>
							</li>
                            <?php
$_smarty_tpl->tpl_vars['a'] = $__foreach_a_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</ul>
					</div>

				</div>
			</li>
		</ul>

	</div>
    <?php }?>

		<div class="collapse navbar-collapse" id="navbar-mobile">

		<?php if (isset($_smarty_tpl->tpl_vars['user_name']->value)) {?>
		<ul class="navbar-nav ml-auto">

			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
					<i class="icon-user mr-3 icon-2x"></i>
					<span><?php echo $_smarty_tpl->tpl_vars['user_name']->value;?>
</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
                    <?php if (!isset($_smarty_tpl->tpl_vars['hide_menu']->value)) {?><a href="mijnaccount/index" class="dropdown-item"><i class="icon-cog5"></i> Mijn account</a><?php }?>
					<a href="login/index?logout" class="dropdown-item"><i class="icon-switch2"></i> Uitloggen</a>
				</div>
			</li>

		</ul>
		<?php }?>

	</div>

</div><?php }
}
