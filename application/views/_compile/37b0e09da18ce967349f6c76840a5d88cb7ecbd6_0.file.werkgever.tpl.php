<?php
/* Smarty version 3.1.33, created on 2020-01-08 20:23:59
  from 'C:\xampp\htdocs\app\application\views\dashboard\werkgever.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e162c4f6ce286_67638547',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '37b0e09da18ce967349f6c76840a5d88cb7ecbd6' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\dashboard\\werkgever.tpl',
      1 => 1578511438,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e162c4f6ce286_67638547 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7688592215e162c4f6a3307_04733455', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4031066355e162c4f6a7181_52963179', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5397971895e162c4f6ab009_80596994', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_542015215e162c4f6aee81_68135042', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_7688592215e162c4f6a3307_04733455 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_7688592215e162c4f6a3307_04733455',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_4031066355e162c4f6a7181_52963179 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_4031066355e162c4f6a7181_52963179',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-home2<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_5397971895e162c4f6ab009_80596994 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_5397971895e162c4f6ab009_80596994',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_542015215e162c4f6aee81_68135042 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_542015215e162c4f6aee81_68135042',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\app\\application\\third_party\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>



	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<!--------------------------------------------------------------------------- left ------------------------------------------------->
			<div class="row">
				<div class="col-md-6">

					<!-- Basic card -->
					<div class="card">

                        						<div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">

                            							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-teal-400">
									<i class="icon-office icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0"><?php echo $_smarty_tpl->tpl_vars['count_uitzenders']->value;?>
</h5>
									<span class="text-muted text-uppercase">Uitzenders</span>
								</div>
							</div>

                            							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-warning-400">
									<i class="icon-user-tie icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0"><?php echo $_smarty_tpl->tpl_vars['count_inleners']->value;?>
</h5>
									<span class="text-muted text-uppercase">Inleners</span>
								</div>
							</div>

                            							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-blue">
									<i class="icon-user icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0"><?php echo $_smarty_tpl->tpl_vars['count_werknemers']->value;?>
</h5>
									<span class="text-muted text-uppercase">Werknemers</span>
								</div>
							</div>

						</div>

						<div class="table-responsive">
							<table class="table text-nowrap">
								<tbody>

									                                    <?php if (count($_smarty_tpl->tpl_vars['uitzenders']->value) > 0) {?>
										<tr class="table-active">
											<td style="max-width:200px ">Nieuwe uitzenders</td>
											<td colspan="3" class="text-right">
												<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/uitzenders/">
													<i class="icon-list-unordered"></i> alle uitzenders
												</a>
											</td>
										</tr>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['uitzenders']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-office icon-lg text-teal-400"></i>
														</div>
														<div>
															<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/uitzenders/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['u']->value['uitzender_id'];?>
" class="text-default font-weight-semibold"><?php echo $_smarty_tpl->tpl_vars['u']->value['bedrijfsnaam'];?>
</a>
															<div class="text-muted font-size-sm">
																<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['u']->value['timestamp'],'%d-%m-%Y om %R');?>

															</div>
														</div>
													</div>
												</td>
												<td></td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php }?>
                                                                        <?php if (count($_smarty_tpl->tpl_vars['inleners']->value) > 0) {?>
										<tr class="table-active">
											<td style="max-width:200px ">Nieuwe inleners</td>
											<td colspan="3" class="text-right">
												<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/inleners/">
													<i class="icon-list-unordered"></i> alle inleners
												</a>
											</td>
										</tr>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['inleners']->value, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value) {
?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-user-tie icon-lg text-warning-400"></i>
														</div>
														<div>
															<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/inleners/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
" class="text-default font-weight-semibold"><?php echo $_smarty_tpl->tpl_vars['i']->value['bedrijfsnaam'];?>
</a>
															<div class="text-muted font-size-sm">
                                                                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['i']->value['timestamp'],'%d-%m-%Y om %R');?>

															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="font-weight-bolder"><?php echo $_smarty_tpl->tpl_vars['i']->value['uitzender'];?>
</div>
													<div class="text-muted">uitzender</div>
												</td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php }?>
                                                                        <?php if (count($_smarty_tpl->tpl_vars['kredietaanvragen']->value) > 0) {?>
										<tr class="table-active">
											<td style="max-width:200px ">Kredietaanrvagen</td>
											<td colspan="3" class="text-right">
												<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/inleners/">
													<i class="icon-list-unordered"></i> alle kredietaanvragen
												</a>
											</td>
										</tr>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['kredietaanvragen']->value, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value) {
?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="mi-euro-symbol text-warning-400" style="font-size: 24px;"></i>
														</div>
														<div>
															<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/crm/inleners/dossier/kredietoverzicht/k<?php echo $_smarty_tpl->tpl_vars['k']->value['id'];?>
" class="text-default font-weight-semibold"><?php echo $_smarty_tpl->tpl_vars['k']->value['bedrijfsnaam'];?>
</a>
															<div class="text-muted font-size-sm">
                                                                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['k']->value['timestamp'],'%d-%m-%Y om %R');?>

															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="font-weight-bolder"><?php echo $_smarty_tpl->tpl_vars['k']->value['uitzender'];?>
</div>
													<div class="text-muted">uitzender</div>
												</td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php }?>

								</tbody>
							</table>
						</div>
					</div>


				</div><!-- /col -->
				      <!--------------------------------------------------------------------------- /left ------------------------------------------------->
			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
