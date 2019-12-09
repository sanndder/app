<?php
/* Smarty version 3.1.33, created on 2019-12-05 10:27:31
  from 'C:\xampp\htdocs\app\application\views\crm\werknemers\dossier\documenten_wizard.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de8cd830d0542_90387304',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44fde9845dea8a8c4872a1ba14ad5e68a4902d3c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\werknemers\\dossier\\documenten_wizard.tpl',
      1 => 1573728544,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/werknemers/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5de8cd830d0542_90387304 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17884151275de8cd8308dec2_58462116', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16637313615de8cd83091d47_87312153', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17367876785de8cd83095bc6_59270270', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11376196775de8cd830a1746_69689746', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_17884151275de8cd8308dec2_58462116 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_17884151275de8cd8308dec2_58462116',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_16637313615de8cd83091d47_87312153 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_16637313615de8cd83091d47_87312153',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_17367876785de8cd83095bc6_59270270 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_17367876785de8cd83095bc6_59270270',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer - <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_11376196775de8cd830a1746_69689746 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_11376196775de8cd830a1746_69689746',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\app\\application\\third_party\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>


    <?php $_smarty_tpl->_subTemplateRender('file:crm/werknemers/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'documenten'), 0, false);
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

							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">ID bewijs uploaden</legend>


								<div class="row">

									<!-- voorkant -->
									<div class="col-xl-5 col-lg-12">

										<div class="mb-3 font-weight-semibold">Voorkant</div>

										<!-- script uploader 1 -->
										<?php echo '<script'; ?>
>
                                            
                                            $( document ).ready( function() {
                                                $( '#fileupload' ).fileinput( 'refresh', {uploadUrl: 'upload/uploadwerknemerid/<?php echo $_smarty_tpl->tpl_vars['werknemer']->value->werknemer_id;?>
/1'} );
                                                $( '#fileupload' ).on( 'filebatchselected', function( event, files ) {
                                                    $( '#fileupload' ).fileinput( "upload" );
                                                } ).on( 'fileuploaded', function( event, data ) {
                                                    $( '#fileupload' ).fileinput( 'clear' );
                                                    $( '#form1' ).hide();
                                                    $( '.img-voorkant' ).show().find( 'img' ).attr( 'src', data.response.url );
                                                    $( '.div-achterkant' ).show();
                                                } );
                                            } );
                                            
										<?php echo '</script'; ?>
>

										<!-- form -->
										<form id="form1" action="#" style="<?php if ($_smarty_tpl->tpl_vars['id_voorkant']->value !== NULL) {?>display:none;<?php }?>">
											<input name="file" type="file" id="fileupload" class="file-input">
										</form>

										<!-- plaatje -->
										<div class="img-voorkant" style="<?php if ($_smarty_tpl->tpl_vars['id_voorkant']->value === NULL) {?>display:none;<?php }?>">
											<img class="img-idbewijs mb-2" style="max-width: 400px; max-height: 300px;" src="<?php echo $_smarty_tpl->tpl_vars['id_voorkant']->value;?>
"/>
											<a href="javascript:void(0)" onclick="deleteIDbewijs( <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->werknemer_id;?>
, 'voorkant')" class="text-danger">
												<i class="icon-trash mr-1"></i>
												ID bewijs verwijderen
											</a>
										</div>

									</div><!-- /voorkant -->

									<!-- achterkant -->
									<div class="col-xl-5 col-lg-12 div-achterkant" style="<?php if ($_smarty_tpl->tpl_vars['id_voorkant']->value === NULL) {?>display:none;<?php }?>">

										<div class="mb-3 font-weight-semibold">Achterkant</div>

										<!-- script uploader 2 -->
										<?php echo '<script'; ?>
>
                                            
                                            $( document ).ready( function() {
                                                $( '#fileupload2' ).fileinput( 'refresh', {uploadUrl: 'upload/uploadwerknemerid/<?php echo $_smarty_tpl->tpl_vars['werknemer']->value->werknemer_id;?>
/2'} );
                                                $( '#fileupload2' ).on( 'filebatchselected', function( event, files ) {
                                                    $( '#fileupload2' ).fileinput( "upload" );
                                                } ).on( 'fileuploaded', function( event, data ) {
                                                    $( '#fileupload2' ).fileinput( 'clear' );
                                                    $( '#form2' ).hide();
                                                    $( '.img-achterkant' ).show().find( 'img' ).attr( 'src', data.response.url );
                                                } );
                                            } );
                                            
										<?php echo '</script'; ?>
>

										<!-- script uploader 2 -->
										<form id="form2" action="#">
											<input name="file" type="file" id="fileupload2" class="file-input">
										</form>

										<!-- plaatje -->
										<div class="img-achterkant" style="<?php if ($_smarty_tpl->tpl_vars['id_achterkant']->value === NULL) {?>display:none;<?php }?>">
											<img class="img-idbewijs mb-2" style="max-width: 400px; max-height: 300px;" src="<?php echo $_smarty_tpl->tpl_vars['id_achterkant']->value;?>
"/>
											<a href="javascript:void(0)" onclick="deleteIDbewijs( <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->werknemer_id;?>
, 'achterkant')" class="text-danger">
												<i class="icon-trash mr-1"></i>
												ID bewijs verwijderen
											</a>
										</div>

									</div><!-- /achterkant -->

								</div><!-- /row -->

							</fieldset>

							<form method="post" action="">
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">ID bewijs gegevens</legend>


									<div class="form-group row">
										<label class="col-lg-2 col-form-label">Vervaldatum:</label>
										<div class="col-lg-6 text-right mb-3">
											<input required name="vervaldatum" value="<?php if (isset($_POST['vervaldatum'])) {
echo $_POST['vervaldatum'];
} else {
if ($_smarty_tpl->tpl_vars['vervaldatum']->value !== NULL) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['vervaldatum']->value,'%d-%m-%Y');
}
}?>" type="text" class="form-control pickadate-id" style="width: 130px;"/>
										</div>
									</div>
								</fieldset>

								<button type="submit" name="set_wizard" class="btn btn-success btn-sm">
									<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
								</button>
							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div>
			<!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
