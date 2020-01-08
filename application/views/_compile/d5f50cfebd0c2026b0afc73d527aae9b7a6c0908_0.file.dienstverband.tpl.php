<?php
/* Smarty version 3.1.33, created on 2020-01-07 23:02:56
  from 'C:\xampp\htdocs\app\application\views\crm\werknemers\dossier\dienstverband.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e150010ca0fc8_77288994',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd5f50cfebd0c2026b0afc73d527aae9b7a6c0908' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\werknemers\\dossier\\dienstverband.tpl',
      1 => 1575452877,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/werknemers/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5e150010ca0fc8_77288994 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_314378575e150010c6a4c5_43320004', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_266647605e150010c6e340_22241883', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7002887655e150010c721c1_17602658', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21389758785e150010c79ec5_53886418', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_314378575e150010c6a4c5_43320004 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_314378575e150010c6a4c5_43320004',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_266647605e150010c6e340_22241883 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_266647605e150010c6e340_22241883',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_7002887655e150010c721c1_17602658 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_7002887655e150010c721c1_17602658',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer - <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_21389758785e150010c79ec5_53886418 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_21389758785e150010c79ec5_53886418',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/werknemers/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'algemeneinstellingen'), 0, false);
?>


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
			<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
				<div class="row">
					<div class="col-xl-10">
						<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

					</div><!-- /col -->
				</div>
				<!-- /row -->
			<?php }?>

			<div class="row">
				<div class="col-xl-10">

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Standaard factoren
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">


																<?php $_smarty_tpl->_assignInScope('label_lg', "3");?>
								<?php $_smarty_tpl->_assignInScope('div_xl', "8");?>
								<?php $_smarty_tpl->_assignInScope('div_md', "8");?>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Standaard factoren</legend>
									<div class="mb-3">Deze factoren worden overgenomen voor nieuw aangemelde inleners.</div>

									<!-- factor_hoog -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['factor_hoog'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "factor_hoog");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php if (is_numeric($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'])) {
echo number_format($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'],3,',','.');
}?>" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
												<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
													<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br/>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
											</div>
										</div>
									<?php }?>

									<!-- factor_laag -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['factor_laag'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "factor_laag");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php if (is_numeric($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'])) {
echo number_format($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'],3,',','.');
}?>" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
												<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
													<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br/>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
											</div>
										</div>
									<?php }?>

								</fieldset>


								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" value="werknemers_factoren" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
									</div><!-- /col -->
								</div><!-- /row -->

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

					

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
