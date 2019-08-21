<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:23:49
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\contactpersonen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b335578b017_54545000',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e2086230c922b46827affc8e2352aa4d5c371ccc' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\contactpersonen.tpl',
      1 => 1565100513,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/inleners/dossier/_sidebar.tpl' => 1,
    'file:crm/inleners/dossier/modals/contactpersonen.tpl' => 1,
  ),
),false)) {
function content_5d4b335578b017_54545000 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2308416695d4b335576bc09_21766511', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12319751775d4b335576fa82_10025826', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20596168615d4b3355773903_17868531', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12583753515d4b3355777780_36776539', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_2308416695d4b335576bc09_21766511 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_2308416695d4b335576bc09_21766511',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_12319751775d4b335576fa82_10025826 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_12319751775d4b335576fa82_10025826',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_20596168615d4b3355773903_17868531 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_20596168615d4b3355773903_17868531',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_12583753515d4b3355777780_36776539 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_12583753515d4b3355777780_36776539',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'contactpersonen'), 0, false);
?>
	<?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/modals/contactpersonen.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
											<button type="button" class="btn btn-light btn-sm" data-id="0" onclick="modalContact(this, 'inlener', <?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
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
" type="button" class="btn btn-outline-info btn-icon rounded-round ml-1" onclick="modalContact(this, 'inlener', <?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
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
