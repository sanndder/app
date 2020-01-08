<?php
/* Smarty version 3.1.33, created on 2020-01-06 07:59:52
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\bedrijfsgegevens.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e12dae8a142a1_59560533',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7dcbd95fccb080a37036cd9096f86a99722132a5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\bedrijfsgegevens.tpl',
      1 => 1572875242,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
    'file:instellingen/werkgever/_topbar.tpl' => 1,
  ),
),false)) {
function content_5e12dae8a142a1_59560533 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16299619195e12dae89a2e14_99387860', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4221829435e12dae89a6c99_16031562', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11408052395e12dae89aab11_43187634', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_71428495e12dae89ae992_20988774', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_16299619195e12dae89a2e14_99387860 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_16299619195e12dae89a2e14_99387860',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_4221829435e12dae89a6c99_16031562 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_4221829435e12dae89a6c99_16031562',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_11408052395e12dae89aab11_43187634 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_11408052395e12dae89aab11_43187634',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_71428495e12dae89ae992_20988774 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_71428495e12dae89ae992_20988774',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'bedrijfsgegevens'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_topbar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Bedrijfsgegevens
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Bedrijfsgegevens</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

						<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
						<div class="row">
							<div class="col-md-12">
								<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

							</div><!-- /col -->
						</div><!-- /row -->
						<?php }?>

						<div class="row">
							<div class="col-xl-6 col-lg-12">

								<!-- bedrijfsnaam -->
								<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['bedrijfsnaam'])) {?>
									<?php $_smarty_tpl->_assignInScope('field', "bedrijfsnaam");?>
									<div class="form-group row">
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
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
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
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
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
										</div>
									</div>
								<?php }?>

							</div><!-- /col -->
							<div class="col-xl-6 col-lg-12">

								<!-- straat -->
								<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['straat'])) {?>
									<?php $_smarty_tpl->_assignInScope('field', "straat");?>
									<div class="form-group row">
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
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
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
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
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
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
										<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>
:</label>
										<div class="col-xl-8 col-md-8">
											<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
												<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br /><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
										</div>
									</div>
								<?php }?>

							</div><!-- /col -->
						</div><!-- /row -->

						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success"><i class="icon-checkmark2 mr-1"></i>Opslaan</button>
							</div><!-- /col -->
						</div><!-- /row -->

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->



			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Ondertekening
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Handtekening</h5>
				</div>

				<div class="card-body">

					<div class="row">
						<div class="col-xl-6 col-lg-12">

                                                        <?php if ($_smarty_tpl->tpl_vars['handtekening']->value === NULL) {?>
								<?php echo '<script'; ?>
>
                                    
                                    $(document).ready(function ()
                                    {
                                        $('#fileupload2').fileinput('refresh', {uploadUrl: 'upload/uploadhantekeningwerkgever/<?php echo $_SESSION['entiteit_id'];?>
'});
                                        $('#fileupload2').on('fileuploaded', function() {
                                            window.location.reload();
                                        });

                                    });
                                    
								<?php echo '</script'; ?>
>

								<form action="#">
									<input name="file" type="file" id="fileupload2" class="file-input">
								</form>
                            <?php } else { ?>

								<img src="<?php echo $_smarty_tpl->tpl_vars['handtekening']->value;?>
" style="max-width: 400px; max-height: 200px;" />
								<br />
								<br />
								<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/instellingen/werkgever/bedrijfsgegevens/?delhandtekening" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Handtekening verwijderen</a>
                            <?php }?>

						</div><!-- /col -->
					</div><!-- /row -->

				</div><!-- /card body -->
			</div><!-- /basic card -->


			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Logo
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Logo</h5>
				</div>

				<div class="card-body">

					<div class="row">
						<div class="col-xl-6 col-lg-12">

                                                        <?php if ($_smarty_tpl->tpl_vars['logo']->value === NULL) {?>
								<?php echo '<script'; ?>
>
                                    
                                    $(document).ready(function ()
                                    {
                                        $('#fileupload').fileinput('refresh', {uploadUrl: 'upload/uploadlogowerkgever/<?php echo $_SESSION['entiteit_id'];?>
'});
                                        $('#fileupload').on('fileuploaded', function() {
                                            window.location.reload();
                                        });

                                    });
                                    
								<?php echo '</script'; ?>
>

								<form action="#">
									<input name="file" type="file" id="fileupload" class="file-input">
								</form>
                            <?php } else { ?>

								<img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" style="max-width: 500px; max-height: 300px;" />
								<br />
								<br />
								<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/instellingen/werkgever/bedrijfsgegevens/?dellogo" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Logo verwijderen</a>
                            <?php }?>

						</div><!-- /col -->

					</div><!-- /row -->

				</div><!-- /card body -->
			</div><!-- /basic card -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
