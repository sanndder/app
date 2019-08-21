<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:16:04
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\bedrijfsgegevens.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b318444c803_56169141',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c067cef722921f4eb36120e4ca3719c75c67f612' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\bedrijfsgegevens.tpl',
      1 => 1565100522,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/inleners/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b318444c803_56169141 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6035673105d4b31843ead76_76351109', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3397034065d4b31843eebf2_45211762', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9186766095d4b31843f2a75_55335034', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19107400205d4b31843fa777_63484239', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_6035673105d4b31843ead76_76351109 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_6035673105d4b31843ead76_76351109',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_3397034065d4b31843eebf2_45211762 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_3397034065d4b31843eebf2_45211762',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_9186766095d4b31843f2a75_55335034 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_9186766095d4b31843f2a75_55335034',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['inlener']->value->inlener_id == 0) {?>
		Nieuwe inlener aanmelden
	<?php } else { ?>
		Inlener - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;?>

	<?php }?>

<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_19107400205d4b31843fa777_63484239 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_19107400205d4b31843fa777_63484239',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'bedrijfsgegevens'), 0, false);
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

								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
									</div><!-- /col -->
								</div><!-- /row -->

																<?php $_smarty_tpl->_assignInScope('label_lg', "3");?>
								<?php $_smarty_tpl->_assignInScope('div_xl', "8");?>
								<?php $_smarty_tpl->_assignInScope('div_md', "8");?>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bedrijfsgegevens</legend>
									<!-- bedrijfsnaam -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['bedrijfsnaam'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "bedrijfsnaam");?>
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

									<!-- kvknr -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['kvknr'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "kvknr");?>
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

									<!-- btwnr -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['btwnr'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "btwnr");?>
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

									<!-- btwnr -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['telefoon'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "telefoon");?>
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
								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bezoekadres</legend>

									<!-- straat -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['straat'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "straat");?>
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

									<!-- huisnummer -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['huisnummer'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "huisnummer");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input style="width: 100px;" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
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

									<!-- huisnummer_toevoeging -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['huisnummer_toevoeging'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "huisnummer_toevoeging");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input style="width: 100px;" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
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

									<!-- postcode -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['postcode'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "postcode");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input style="width: 100px;" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
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

									<!-- plaats -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['plaats'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "plaats");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input style="width: 100px;" value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
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
								</fieldset>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Postbus</legend>

									<!-- postbus_nummer -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['postbus_nummer'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "postbus_nummer");?>
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

									<!-- postbus_postcode -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['postbus_postcode'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "postbus_postcode");?>
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

									<!-- postbus_plaats -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['postbus_plaats'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "postbus_plaats");?>
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
