<?php
/* Smarty version 3.1.33, created on 2020-01-07 23:02:58
  from 'C:\xampp\htdocs\app\application\views\crm\werknemers\dossier\gegevens.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e150012375a42_90456373',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5722b07eeb22b99b37b70e18d1047b84fff5c573' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\werknemers\\dossier\\gegevens.tpl',
      1 => 1576157029,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/werknemers/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5e150012375a42_90456373 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19645043565e1500122e1327_14816260', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8047105545e1500122e51a4_82120906', "header-icon");
?>

<?php $_smarty_tpl->_assignInScope('select2', "true");
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16478098875e1500122e9024_88988535', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5401899655e1500122f0d24_03634337', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_19645043565e1500122e1327_14816260 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_19645043565e1500122e1327_14816260',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_8047105545e1500122e51a4_82120906 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_8047105545e1500122e51a4_82120906',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_16478098875e1500122e9024_88988535 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_16478098875e1500122e9024_88988535',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['werknemer']->value->werknemer_id == 0) {?>
		Nieuwe werknemer aanmelden
	<?php } else { ?>
		Werknemer - <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;?>

	<?php }?>

<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_5401899655e1500122f0d24_03634337 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_5401899655e1500122f0d24_03634337',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/werknemers/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'gegevens'), 0, false);
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
									<div class="col-lg-6 mb-3">
                                        <?php if ($_smarty_tpl->tpl_vars['werknemer']->value->complete == 1) {?>
											<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
                                        <?php }?>
									</div><!-- /col -->
									<?php if ($_smarty_tpl->tpl_vars['ENV']->value == 'development') {?>
									<div class="col-lg-6 text-right mb-3">
										<span data-title="Formulier invullen" data-popup="tooltip" data-placement="top" style="cursor:pointer;" onclick="fillForm()">
											<i class="icon-pencil3 mr-2" style="font-size: 22px"></i>
										</span>
									</div>
									<?php }?>
								</div><!-- /row -->

																<?php $_smarty_tpl->_assignInScope('label_lg', "3");?>
								<?php $_smarty_tpl->_assignInScope('div_xl', "8");?>
								<?php $_smarty_tpl->_assignInScope('div_md', "8");?>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Persoonsgegevens</legend>

									<!-- geslacht -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['geslacht'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "geslacht");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<select name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" style="width: 150px">
                                                    <?php if (!isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['empty'])) {?>
														<option value=""></option>
                                                    <?php }?>
                                                    <?php if (is_array($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options'])) {?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']);?>
                                                    <?php } else { ?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['list']->value[$_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']]);?>
                                                    <?php }?>
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['options']->value, 'option');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$__foreach_option_0_saved = $_smarty_tpl->tpl_vars['option'];
?>
														<option <?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>selected=""<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['option']->key;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</option>
                                                    <?php
$_smarty_tpl->tpl_vars['option'] = $__foreach_option_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</select>

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

									<!-- voorletters -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['voorletters'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "voorletters");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-2 col-md-4">
												<input value="<?php if (isset($_smarty_tpl->tpl_vars['carddata']->value['voorletters'])) {
echo $_smarty_tpl->tpl_vars['carddata']->value['voorletters'];
} else {
echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];
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

									<!-- voornaam -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['voornaam'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "voornaam");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php if (isset($_smarty_tpl->tpl_vars['carddata']->value['voornaam'])) {
echo $_smarty_tpl->tpl_vars['carddata']->value['voornaam'];
} else {
echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];
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

									<!-- tussenvoegsel -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['tussenvoegsel'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "tussenvoegsel");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-2 col-md-4">
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

									<!-- achternaam -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['achternaam'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "achternaam");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php if (isset($_smarty_tpl->tpl_vars['carddata']->value['achternaam'])) {
echo $_smarty_tpl->tpl_vars['carddata']->value['achternaam'];
} else {
echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];
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

									<!-- achternaam -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['bsn'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "bsn");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php if (isset($_smarty_tpl->tpl_vars['carddata']->value['achternaam'])) {
echo $_smarty_tpl->tpl_vars['carddata']->value['achternaam'];
} else {
echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];
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


									<!-- gb datum -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['gb_datum'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "gb_datum");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<div class="input-group"  style="width: 250px;">
													<input value="<?php if (isset($_smarty_tpl->tpl_vars['carddata']->value['achternaam'])) {
echo $_smarty_tpl->tpl_vars['carddata']->value['achternaam'];
} else {
echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];
}?>" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="date" class="form-control input-picker<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
													<span class="input-group-prepend show-datepicker">
														<span class="input-group-text <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" style="cursor: pointer">
															<i class="icon-calendar3"></i>
														</span>
													</span>
												</div>
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

									<!-- nationaltieit_id -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['nationaltieit_id'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "nationaltieit_id");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<select name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" class="form-control select-search<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>-error<?php }?>" id="div-<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
">
                                                    <?php if (!isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['empty'])) {?>
														<option value="">Selecteer een nationaliteit</option>
                                                    <?php }?>
                                                    <?php if (is_array($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options'])) {?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']);?>
                                                    <?php } else { ?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['list']->value[$_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']]);?>
                                                    <?php }?>
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['options']->value, 'option');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$__foreach_option_8_saved = $_smarty_tpl->tpl_vars['option'];
?>
														<option <?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>selected=""<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['option']->key;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</option>
                                                    <?php
$_smarty_tpl->tpl_vars['option'] = $__foreach_option_8_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</select>

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
									<legend class="text-uppercase font-size-sm font-weight-bold">Contactgegevens</legend>

									<!-- telefoon -->
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

									<!-- mobiel -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['mobiel'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "mobiel");?>
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

									<!-- email -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['email'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "email");?>
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

									<br />

									<!-- woonland -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['woonland_id'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "woonland_id");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:</label>
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<select name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" class="form-control select-search">
                                                    <?php if (!isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['empty'])) {?>
														<option value="">Selecteer een nationaliteit</option>
                                                    <?php }?>
                                                    <?php if (is_array($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options'])) {?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']);?>
                                                    <?php } else { ?>
                                                        <?php $_smarty_tpl->_assignInScope('options', $_smarty_tpl->tpl_vars['list']->value[$_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['list']['options']]);?>
                                                    <?php }?>
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['options']->value, 'option');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value) {
$__foreach_option_13_saved = $_smarty_tpl->tpl_vars['option'];
?>
														<option <?php if ($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'] == $_smarty_tpl->tpl_vars['option']->key) {?>selected=""<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['option']->key;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
</option>
                                                    <?php
$_smarty_tpl->tpl_vars['option'] = $__foreach_option_13_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</select>

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
									<legend class="text-uppercase font-size-sm font-weight-bold">Bankgegevens</legend>

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

                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['tav'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "tav");?>
										<div class="form-group row">
											<label class="col-lg-<?php echo $_smarty_tpl->tpl_vars['label_lg']->value;?>
 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

												:<br />
												<i style="font-size: 11px">Alleen bij afwijkende naam, bijvoorbeeld van een bewindvoerder.</i>
											</label>
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
