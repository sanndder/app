<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:25:33
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\factuurgegevens.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b33bd3ae774_76133418',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e043379f8f396978d98da09a93544f74305df7eb' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\factuurgegevens.tpl',
      1 => 1565017267,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/uitzenders/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b33bd3ae774_76133418 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7091950025d4b33bd36ff69_53300777', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8531862795d4b33bd373de7_93098835', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19083286575d4b33bd377c61_51044307', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20621298745d4b33bd37bae5_61244655', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_7091950025d4b33bd36ff69_53300777 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_7091950025d4b33bd36ff69_53300777',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_8531862795d4b33bd373de7_93098835 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_8531862795d4b33bd373de7_93098835',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_19083286575d4b33bd377c61_51044307 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_19083286575d4b33bd377c61_51044307',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_20621298745d4b33bd37bae5_61244655 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_20621298745d4b33bd37bae5_61244655',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'factuurgegevens'), 0, false);
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

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">


																<?php $_smarty_tpl->_assignInScope('label_lg', "3");?>
								<?php $_smarty_tpl->_assignInScope('div_xl', "8");?>
								<?php $_smarty_tpl->_assignInScope('div_md', "8");?>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Factuurgegevens</legend>

									<!-- iban -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['iban'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "iban");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
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

									<!-- Ter attentie van -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['tav'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "tav");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
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

									<!-- Factuur emailen -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['factuur_emailen'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "factuur_emailen");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['options'], 'option');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$__foreach_option_2_saved = $_smarty_tpl->tpl_vars['option'];
?>
												<div class="form-check <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['inline']) && $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['inline'] == true) {?>form-check-inline<?php }?>">
													<label class="form-check-label">
														<span class="<?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>checked<?php }?>">
															<input value="<?php echo $_smarty_tpl->tpl_vars['option']->key;?>
" type="radio" class="form-input-styled" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>checked=""<?php }?>>
														</span>
														<?php echo $_smarty_tpl->tpl_vars['option']->value;?>

													</label>
												</div>
												<?php
$_smarty_tpl->tpl_vars['option'] = $__foreach_option_2_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

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

									<!-- bijlages_invoegen -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['bijlages_invoegen'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "bijlages_invoegen");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['options'], 'option');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$__foreach_option_4_saved = $_smarty_tpl->tpl_vars['option'];
?>
													<div class="form-check <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['inline']) && $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['radio']['inline'] == true) {?>form-check-inline<?php }?>">
														<label class="form-check-label">
														<span class="<?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>checked<?php }?>">
															<input value="<?php echo $_smarty_tpl->tpl_vars['option']->key;?>
" type="radio" class="form-input-styled" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>checked=""<?php }?>>
														</span>
															<?php echo $_smarty_tpl->tpl_vars['option']->value;?>

														</label>
													</div>
												<?php
$_smarty_tpl->tpl_vars['option'] = $__foreach_option_4_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

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
										<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
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
