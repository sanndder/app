<?php
/* Smarty version 3.1.33, created on 2019-12-05 10:27:13
  from 'C:\xampp\htdocs\app\application\views\facturenoverzicht\uitzender.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de8cd71be8391_31730261',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cdceb936d473522b83645f22b028d72cea48d10a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\facturenoverzicht\\uitzender.tpl',
      1 => 1575450945,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5de8cd71be8391_31730261 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3266115425de8cd71bd8986_22831641', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10298955725de8cd71bdc802_23434888', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13964234495de8cd71be0685_44775980', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('ckeditor', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14530608405de8cd71be4501_08372999', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_3266115425de8cd71bd8986_22831641 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_3266115425de8cd71bd8986_22831641',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_10298955725de8cd71bdc802_23434888 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_10298955725de8cd71bdc802_23434888',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
mi-euro-symbol<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_13964234495de8cd71be0685_44775980 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_13964234495de8cd71be0685_44775980',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Facturen & Marge<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_14530608405de8cd71be4501_08372999 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_14530608405de8cd71be4501_08372999',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>



	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

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
								<th style="width: 205px;">Factuur</th>
								<th></th>
								<th style="width: 205px;">Kosten</th>
								<th></th>
								<th style="width: 205px;">Marge</th>
								<th></th>
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
								<td>kosten_2019_30.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_30.pdf</td>
								<td>€ 100,00</td>
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
								<td>factuur_2019_29.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_29.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_29.pdf</td>
								<td>€ 100,00</td>
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
								<td>factuur_2019_28.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_28.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_28.pdf</td>
								<td>€ 100,00</td>
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
								<td>factuur_2019_27.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_27.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_27.pdf</td>
								<td>€ 100,00</td>
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


			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
