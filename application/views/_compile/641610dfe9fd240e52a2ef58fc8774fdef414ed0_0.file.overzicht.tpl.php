<?php
/* Smarty version 3.1.33, created on 2019-08-07 14:46:24
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4ac820c3b365_46135182',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '641610dfe9fd240e52a2ef58fc8774fdef414ed0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\overzicht.tpl',
      1 => 1565180546,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/uitzenders/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4ac820c3b365_46135182 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19123995375d4ac820c23c66_29615677', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10228456305d4ac820c27ae5_08554410', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21013453835d4ac820c2b966_83952985', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1100089815d4ac820c2f7e1_04324489', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_19123995375d4ac820c23c66_29615677 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_19123995375d4ac820c23c66_29615677',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_10228456305d4ac820c27ae5_08554410 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_10228456305d4ac820c27ae5_08554410',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_21013453835d4ac820c2b966_83952985 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_21013453835d4ac820c2b966_83952985',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_1100089815d4ac820c2f7e1_04324489 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_1100089815d4ac820c2f7e1_04324489',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'overzicht'), 0, false);
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

								<img style="max-width: 300px; max-height: 120px;" class="align-self-start mr-4 d-none d-lg-block" src="<?php echo $_smarty_tpl->tpl_vars['uitzender']->value->logo('url');?>
">

								<div class="media-body">
									<div class="row">
										<div class="col-md-12">
											<h5 class="mt-0"><?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;?>
</h5>
										</div><!-- /col -->
									</div><!-- /row -->

									<div class="row">
										<div class="col-md-6 col-xxl-3">

											<ul class="list-unstyled">
												<li><?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['straat'];?>
 <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['huisnummer'];?>
 <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['huisnummer_toevoeging'];?>
</li>
												<li><?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['postcode'];?>
 <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['plaats'];?>
</li>
												<li class="mt-2"></li>
												<li><?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['telefoon'];?>
</li>
												<li><?php echo $_smarty_tpl->tpl_vars['emailadressen']->value['standaard'];?>
</li>
											</ul>

										</div><!-- /col -->
										<div class="col-md-6 col-xxl-3">
											<ul class="list-unstyled">
												<li>KvK: <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['kvknr'];?>
</li>
												<li>BTW: <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['btwnr'];?>
</li>
												<li class="mt-2"></li>
												<?php if ($_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['postbus_nummer'] != NULL) {?>
													<li>Postbus <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['postbus_nummer'];?>
</li>
													<li><?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['postbus_postcode'];?>
 <?php echo $_smarty_tpl->tpl_vars['bedrijfsgegevens']->value['postbus_plaats'];?>
</li>
												<?php }?>
											</ul>
										</div>
									</div><!-- /row -->

								</div>
							</div>


						</div><!-- /card body-->
					</div><!-- /card: Bedrijfsgegevens  -->

					<!---------------------------------------------------- card: Facturen ------------------------------------------------------>
					<div class="card">


						<div class="card-header header-elements-inline">
							<h5 class="card-title">Recente facturen</h5>
						</div>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th style="width: 25px;">Jaar</th>
									<th style="width: 25px;">Periode</th>
									<th style="width: 25px;">Nr.</th>
									<th>PDF</th>
									<th>Bedrag excl.</th>
									<th style="width: 25px"></th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td>2019</td>
									<td>30</td>
									<td>45669</td>
									<td>factuur_2019_30.pdf</td>
									<td>€ 250,59</td>
									<td>
										<ul class="list-inline mb-0 mt-2 mt-sm-0">
											<li class="list-inline-item dropdown">
												<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

												<div class="dropdown-menu dropdown-menu-right">
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
												</div>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>2019</td>
									<td>29</td>
									<td>45659</td>
									<td>factuur_2019_30.pdf</td>
									<td>€ 250,59</td>
									<td>
										<ul class="list-inline mb-0 mt-2 mt-sm-0">
											<li class="list-inline-item dropdown">
												<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

												<div class="dropdown-menu dropdown-menu-right">
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
												</div>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>2019</td>
									<td>28</td>
									<td>45449</td>
									<td>factuur_2019_30.pdf</td>
									<td>€ 250,59</td>
									<td>
										<ul class="list-inline mb-0 mt-2 mt-sm-0">
											<li class="list-inline-item dropdown">
												<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

												<div class="dropdown-menu dropdown-menu-right">
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
												</div>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td>2019</td>
									<td>27</td>
									<td>45379</td>
									<td>factuur_2019_30.pdf</td>
									<td>€ 250,59</td>
									<td>
										<ul class="list-inline mb-0 mt-2 mt-sm-0">
											<li class="list-inline-item dropdown">
												<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

												<div class="dropdown-menu dropdown-menu-right">
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
													<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
												</div>
											</li>
										</ul>
									</td>
								</tr>
								</tbody>
							</table>
						</div>


					</div><!-- /card: Facturen  -->

				</div><!-- / left side -->
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Right side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Accountmanager</span>
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
										<a href="#" class="media-title font-weight-semibold">peterdegrootte</a>
										<div class="font-size-sm text-muted">Peter de Grootte</div>
									</div>
									<div class="ml-3 align-self-center">
										<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Login als">
											<i class="icon-enter"></i>
										</a>
									</div>
								</li>

								<li class="media mt-2">
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">sandra_m</a>
										<div class="font-size-sm text-muted">Sandra Mettens</div>
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
