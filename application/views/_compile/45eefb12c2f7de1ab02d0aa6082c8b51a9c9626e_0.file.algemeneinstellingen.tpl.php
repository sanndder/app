<?php
/* Smarty version 3.1.33, created on 2019-08-07 15:42:03
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\algemeneinstellingen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4ad52b978104_58067286',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '45eefb12c2f7de1ab02d0aa6082c8b51a9c9626e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\algemeneinstellingen.tpl',
      1 => 1565094995,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/uitzenders/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4ad52b978104_58067286 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16436011265d4ad52b935a77_89343538', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12958694115d4ad52b9398f4_36801858', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7600145905d4ad52b93d772_22234094', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20154432505d4ad52b945478_70483994', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_16436011265d4ad52b935a77_89343538 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_16436011265d4ad52b935a77_89343538',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_12958694115d4ad52b9398f4_36801858 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_12958694115d4ad52b9398f4_36801858',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_7600145905d4ad52b93d772_22234094 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_7600145905d4ad52b93d772_22234094',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_20154432505d4ad52b945478_70483994 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_20154432505d4ad52b945478_70483994',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'algemeneinstellingen'), 0, false);
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

									<!-- factor_normaal -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['factor_normaal'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "factor_normaal");?>
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

									<!-- factor_overuren -->
									<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['factor_overuren'])) {?>
										<?php $_smarty_tpl->_assignInScope('field', "factor_overuren");?>
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
										<button type="submit" name="set" value="uitzenders_factoren" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
									</div><!-- /col -->
								</div><!-- /row -->

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Handtekening
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Handtekening</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-xl-6 col-lg-12">

																		<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->handtekening() === NULL) {?>
										<?php echo '<script'; ?>
>
											
                                            $(document).ready(function ()
                                            {
                                                $('#fileupload2').fileinput('refresh', {uploadUrl: 'upload/uploadhantekeninguitzender/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
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

										<img src="<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->handtekening('url');?>
" />
										<br />
										<br />
										<a href="crm/uitzenders/dossier/algemeneinstellingen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
?delhandtekening" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Handtekening verwijderen</a>
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

																		<?php if ($_smarty_tpl->tpl_vars['uitzender']->value->logo() === NULL) {?>
										<?php echo '<script'; ?>
>
											
											$(document).ready(function ()
											{
												$('#fileupload').fileinput('refresh', {uploadUrl: 'upload/uploadlogouitzender/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
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

										<img src="<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->logo('url');?>
" />
										<br />
										<br />
										<a href="crm/uitzenders/dossier/algemeneinstellingen/<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
?dellogo" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Logo verwijderen</a>
									<?php }?>

								</div><!-- /col -->

							</div><!-- /row -->

						</div><!-- /card body -->
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
