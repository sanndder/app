<?php
/* Smarty version 3.1.33, created on 2019-12-04 15:49:01
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\_topbar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de7c75d4d2ea8_34258899',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '47bead87af9a37043066663fd744b4a43a3ba5c7' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\_topbar.tpl',
      1 => 1575369994,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5de7c75d4d2ea8_34258899 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\app\\application\\third_party\\smarty\\plugins\\modifier.replace.php','function'=>'smarty_modifier_replace',),));
?>
	<!-- Top bar -->

	<div class="card">
		<div class="card-body pb-2 pt-2 d-flex justify-content-between">

				<?php if (count($_smarty_tpl->tpl_vars['entiteiten']->value) == 1) {?>
					<span class="pt-1">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['entiteiten']->value, 'entiteit');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['entiteit']->value) {
?>
							<?php echo $_smarty_tpl->tpl_vars['entiteit']->value['schermnaam'];?>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</span>
				<?php } else { ?>
					<ul class="list-inline list-inline-condensed mb-0">
						<li class="list-inline-item">Geselecteerde entiteit: </li>
						<li class="list-inline-item dropdown pl-0">
							<a href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2 pt-1" data-toggle="dropdown" style="width: 100px;">
	                            <?php echo $_smarty_tpl->tpl_vars['entiteiten']->value[$_SESSION['entiteit_id']]['entiteit_id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['entiteiten']->value[$_SESSION['entiteit_id']]['schermnaam'];?>

							</a>
							<div class="dropdown-menu">
	                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['entiteiten']->value, 'entiteit');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['entiteit']->value) {
?>
	                                <?php if ($_smarty_tpl->tpl_vars['entiteit']->value['entiteit_id'] != $_SESSION['entiteit_id']) {?>
										<a href="<?php echo $_smarty_tpl->tpl_vars['current_url']->value;?>
?entity_id=<?php echo $_smarty_tpl->tpl_vars['entiteit']->value['entiteit_id'];
if ($_smarty_tpl->tpl_vars['qs']->value != '') {?>&<?php echo smarty_modifier_replace(smarty_modifier_replace($_smarty_tpl->tpl_vars['qs']->value,$_smarty_tpl->tpl_vars['replace']->value,''),'&&','');
}?>" class="dropdown-item">
	                                        <?php echo $_smarty_tpl->tpl_vars['entiteit']->value['entiteit_id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['entiteit']->value['schermnaam'];?>

										</a>
	                                <?php }?>
	                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</li>
					</ul>

                <?php }?>


			<div>
				<a class="btn btn-light btn-sm" href="javascript:void(0)">
					<i class="icon-plus-circle2"></i>
					Nieuwe entiteit toevoegen
				</a>
			</div>

		</div>
	</div>

	<!-- /Top bar  -->

<?php }
}
