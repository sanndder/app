<?php
/* Smarty version 3.1.33, created on 2020-01-07 23:02:47
  from 'C:\xampp\htdocs\app\application\views\crm\werknemers\dossier\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e15000760b492_95411151',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29733511776d89a000d3c6d3c6e606a243ca9bd8' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\werknemers\\dossier\\overzicht.tpl',
      1 => 1574178950,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/werknemers/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5e15000760b492_95411151 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17582113775e1500075f3d96_36975374', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4636034065e1500075f7c19_38025501', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17434010835e1500075fba90_71828346', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18809246385e1500075ff918_72436945', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_17582113775e1500075f3d96_36975374 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_17582113775e1500075f3d96_36975374',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_4636034065e1500075f7c19_38025501 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_4636034065e1500075f7c19_38025501',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_17434010835e1500075fba90_71828346 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_17434010835e1500075fba90_71828346',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer - <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_18809246385e1500075ff918_72436945 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_18809246385e1500075ff918_72436945',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/werknemers/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'overzicht'), 0, false);
?>


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Left side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<!----------------------------------------------- card: Bedrijfsgegevens ------------------------------------------------------------->
					<div class="card">
						<div class="card-body">

							<div class="media">

								<img style="max-width: 300px; max-height: 120px;" class="align-self-start mr-4 d-none d-lg-block" src="">

								<div class="media-body">
									<div class="row">
										<div class="col-md-12">
											<h5 class="mt-0"><?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;?>
</h5>
										</div><!-- /col -->
									</div><!-- /row -->

									<div class="row">
										<div class="col-md-6 col-xxl-3">

											<ul class="list-unstyled">
												<li><?php echo $_smarty_tpl->tpl_vars['gegevens']->value['straat'];?>
 <?php echo $_smarty_tpl->tpl_vars['gegevens']->value['huisnummer'];?>
 <?php echo $_smarty_tpl->tpl_vars['gegevens']->value['huisnummer_toevoeging'];?>
</li>
												<li><?php echo $_smarty_tpl->tpl_vars['gegevens']->value['postcode'];?>
 <?php echo $_smarty_tpl->tpl_vars['gegevens']->value['plaats'];?>
</li>
												<li class="mt-2"></li>
												<li><?php echo $_smarty_tpl->tpl_vars['gegevens']->value['telefoon'];?>
</li>
												<li><?php echo $_smarty_tpl->tpl_vars['gegevens']->value['email'];?>
</li>
											</ul>

										</div><!-- /col -->
										<div class="col-md-6 col-xxl-3">

										</div>
									</div><!-- /row -->

								</div>
							</div>


						</div><!-- /card body-->
					</div><!-- /card: Bedrijfsgegevens  -->
				</div><!-- / left side -->

				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Right side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Uitzender</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Wijzig accountmanager">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div><!-- /card: Accountmanager  -->

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Inleners</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Wijzig accountmanager">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div><!-- /card: Accountmanager  -->

					<!------------------------------------------------------- card: Gebruikers --------------------------------------------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Users</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Usermanagement">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

							<ul class="media-list">
								<li class="media mt-0">
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">hsmeijering</a>
										<div class="font-size-sm text-muted">Sander Meijering</div>
									</div>
									<div class="ml-3 align-self-center">
										<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Login als">
											<i class="icon-enter"></i>
										</a>
									</div>
								</li>


							</ul>

						</div>
					</div><!-- /card: Accountmanager  -->

				</div><!-- / Right side -->
			</div><!-- /einde main row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
