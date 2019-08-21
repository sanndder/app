<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:24:31
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\contactpersonen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b337f7a4033_62402987',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8d60630133d5687def1c77049f4227e791cbf8f0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\contactpersonen.tpl',
      1 => 1564752800,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/uitzenders/dossier/_sidebar.tpl' => 1,
    'file:crm/uitzenders/dossier/modals/contactpersonen.tpl' => 1,
  ),
),false)) {
function content_5d4b337f7a4033_62402987 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_77205395d4b337f780db9_83542274', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11564150635d4b337f784c33_35423300', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11019786395d4b337f788ab4_74724260', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17399315915d4b337f78c937_29043635', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_77205395d4b337f780db9_83542274 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_77205395d4b337f780db9_83542274',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_11564150635d4b337f784c33_35423300 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_11564150635d4b337f784c33_35423300',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_11019786395d4b337f788ab4_74724260 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_11019786395d4b337f788ab4_74724260',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_17399315915d4b337f78c937_29043635 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_17399315915d4b337f78c937_29043635',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'contactpersonen'), 0, false);
?>
	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/modals/contactpersonen.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
				<div class="col-md-10">



					<!-- Basic card -->
					<div class="card">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-sm py-header rounded-top">

								<div class="navbar-collapse text-center text-lg-left flex-wrap collapse show" id="inbox-toolbar-toggle-read">
									<div class="mt-3 mt-lg-0 mr-lg-3">
										<div class="btn-group">
											<button type="button" class="btn btn-light btn-sm" data-id="0" onclick="modalContact(this, 'uitzender', <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
)">
												<i class="icon-plus-circle2"></i>
												<span class="d-none d-inline-block ml-2">Contactpersoon toevoegen</span>
											</button>
										</div>
									</div>

									<div class="navbar-text ml-lg-auto"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['contactpersonen']->value, 'contact');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['contact']->value) {
?>
				<div class="row">

					<div class="col-md-10">
						<!-- Basic card -->
						<div class="card">
							<div class="card-body">

								<div class="row">
									<div class="col-md-1 ml-0 text-right">
										<i class="icon-user icon-3x d-none d-lg-block" style="margin-top: -5px"></i>
									</div>

									<div class="col-md-4 mb-2">
										<div class="media-title font-weight-semibold">
											<?php echo $_smarty_tpl->tpl_vars['contact']->value['aanhef'];?>
. <?php echo $_smarty_tpl->tpl_vars['contact']->value['naam'];?>

										</div>
										<span class="text-muted"><?php echo $_smarty_tpl->tpl_vars['contact']->value['functie'];?>
 <?php if ($_smarty_tpl->tpl_vars['contact']->value['afdeling'] != NULL) {?> - <?php echo $_smarty_tpl->tpl_vars['contact']->value['afdeling'];
}?> </span>
									</div>

									<div class="col-md-3 mb-2">
										<ul class="list list-unstyled mb-0">
											<li><i class="icon-phone mr-2"></i> <?php echo $_smarty_tpl->tpl_vars['contact']->value['telefoon'];?>
 </li>
											<li><i class="icon-mail5 mr-2"></i> <?php echo $_smarty_tpl->tpl_vars['contact']->value['email'];?>
</li>
										</ul>
									</div>

									<div class="col-md-3 font-italic">
										<?php if ($_smarty_tpl->tpl_vars['contact']->value['opmerking'] != NULL) {?>
											<?php echo $_smarty_tpl->tpl_vars['contact']->value['opmerking'];?>

										<?php }?>
									</div>

									<div class="col-md-1 text-right">
										<button data-title="Contact persoon wijzigen" data-id="<?php echo $_smarty_tpl->tpl_vars['contact']->value['contact_id'];?>
" type="button" class="btn btn-outline-info btn-icon rounded-round ml-1" onclick="modalContact(this, 'uitzender', <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->uitzender_id;?>
)" data-popup="tooltip" data-placement="top">
											<em class="icon-pencil mr-sm"></em>
										</button>
									</div>

								</div><!-- /row -->

							</div>
						</div>
					</div>
					<!-- /col -->
				</div>
				<!-- /row -->

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
