
<?php

	$v = "";
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('supplier')) {
		$v .= "&supplier=" . $this->input->post('supplier');
	}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('note')) {
		$v .= "&note=" . $this->input->post('note');
	}
	if(isset($date)){
		$v .= "&d=" . $date;
	}

?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>

<script>

	$(document).ready(function(){
        $('body').on('click', '#combine_pay', function(e) {

            e.preventDefault();
            if($('.checkbox').is(":checked") === false){
                alert('Please select at least one.');
                return false;
            }
            var arrItems = [];
            $('.checkbox').each(function(i){
                if($(this).is(":checked")){
                    if(this.value != ""){
                        arrItems[i] = $(this).val();
                    }
                }
            });


            var i = 0;
                var items = [];
                var b=false;
                var k = false;
                $.each($("input[name='val[]']:checked"), function(){
                    items[i] = {'id': $(this).val()};
                    i++;
                });

                $.ajax({
                    type: 'get',
                    url: site.base_url+'account/checkreferPur',
                    dataType: "json",
                    async:false,
                    data: { <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',items:items },
                    success: function (data) {
                        if(data.isAuth == 1){
                            b = true;
                        }
                        if(data.customer == 2){
                            k = true;
                        }
                    }
                });

                if(b == true){
                    bootbox.alert('Customer is not match!');
                    return false;
                }else {
                    $('#myModal').modal({remote: '<?=base_url('sales/combine_payment_pur');?>?data=' + arrItems + ''});
                    $('#myModal').modal('show');
                    return false;
                }

            $('#form_action').val($('#combine_pay').attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

	});

</script>
<script>
    $(document).ready(function () {
        var oTable = $('#POData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true,
			'bServerSide': true,
			"bStateSave": true,
            'sAjaxSource': '<?=site_url('purchases/getPurchases' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];

                var action = $('td:eq(11)', nRow);
				if (aData[10] == 'paid') {
					action.find('.edit').remove();
				}
                if (aData[11] == 1) {
                    action.find('.edit').remove();
                    nRow.className = '';
                } else {
                    action.find('.edit_opening_ap').remove();
                    nRow.className = "purchase_links";
                }

                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null,null,null, {"mRender": row_status},
                {"mRender": currencyFormat}, {"mRender": currencyFormat},
                {"mRender": currencyFormat}, {"mRender": currencyFormat},
                {"mRender": row_status}, {"bVisible": false} , null,  {"bSortable": false}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0, paid = 0, balance = 0, return_purchases = 0;
                for (var i = 0; i < aaData.length; i++) {
                    total += parseFloat(aaData[aiDisplay[i]][7]);
                    return_purchases += parseFloat(aaData[aiDisplay[i]][8]);
                    paid += parseFloat(aaData[aiDisplay[i]][9]);
                    balance += parseFloat(aaData[aiDisplay[i]][10]);

                }

                var nCells = nRow.getElementsByTagName('th');
                nCells[7].innerHTML = currencyFormat(total);
                nCells[8].innerHTML = currencyFormat(return_purchases);
                nCells[9].innerHTML = currencyFormat(paid);
                nCells[10].innerHTML = currencyFormat(balance);

            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
			{column_number: 2, filter_default_label: "[<?=lang('PR_No');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('PO_No');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('ref_no');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
			{column_number: 6, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
			{column_number: 11, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
            {column_number: 13, filter_default_label: "[<?=lang('Create_by');?>]", filter_type: "text", data: []},

        ], "footer");

        <?php if ($this->session->userdata('remove_pols')) {?>
        if (localStorage.getItem('poitems')) {
            localStorage.removeItem('poitems');
        }
        if (localStorage.getItem('podiscount')) {
            localStorage.removeItem('podiscount');
        }
        if (localStorage.getItem('potax2')) {
            localStorage.removeItem('potax2');
        }
        if (localStorage.getItem('poshipping')) {
            localStorage.removeItem('poshipping');
        }
        if (localStorage.getItem('poref')) {
            localStorage.removeItem('poref');
        }
        if (localStorage.getItem('powarehouse')) {
            localStorage.removeItem('powarehouse');
        }
        if (localStorage.getItem('ponote')) {
            localStorage.removeItem('ponote');
        }
        if (localStorage.getItem('posupplier')) {
            localStorage.removeItem('posupplier');
        }
        if (localStorage.getItem('pocurrency')) {
            localStorage.removeItem('pocurrency');
        }
        if (localStorage.getItem('poextras')) {
            localStorage.removeItem('poextras');
        }
        if (localStorage.getItem('podate')) {
            localStorage.removeItem('podate');
        }
        if (localStorage.getItem('postatus')) {
            localStorage.removeItem('postatus');
        }
        <?php $this->erp->unset_data('remove_pols');}
        ?>
    });

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<?php if ($Owner || !$Admin) {
	    echo form_open('purchases/purchase_actions', 'id="action-form"');
	}
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('purchase') . ' (' . (sizeof(explode('-',$warehouse_id))>1 ? lang('all_warehouses') : (!isset($warehouse_id)||$warehouse_id==null?lang('all_warehouses'):$warehouse->name) ) . ')'; ?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
						<li>
                            <a data-target="#myModal" data-toggle="modal" href="javascript:void(0)" id="combine_pay" data-action="combine_pay">
                                <i class="fa fa-money"></i> <?=lang('combine_to_pay')?>
                            </a>
                        </li>
                        <?php if ($Owner || $Admin || $GP['purchases-add']) {?>
							<li>
								<a href="<?=site_url('purchases/add')?>">
									<i class="fa fa-plus-circle"></i> <?=lang('add_purchase')?>
								</a>
							</li>
						<?php } ?>

						<?php if ($Owner || $Admin || $GP['purchases-export']) {?>
							<li>
								<a href="#" id="excel" data-action="export_excel">
									<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
								</a>
							</li>
							<li>
								<a href="#" id="pdf" data-action="export_pdf">
									<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
								</a>
							</li>
						<?php } ?>
						<?php if($Owner || $Admin || $GP['purchases-import']) { ?>
							<li>
								<a class="submenu" href="<?= site_url('purchases/purchase_by_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_purchase'); ?></span>
								</a>
							</li>
						<?php }?>
						<?php if ($Owner || $Admin || $GP['purchases-import_expanse']) {?>
							<li>
								<a class="submenu" href="<?= site_url('purchases/expense_by_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_expense'); ?></span>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['purchases-combine_pdf']) {?>
							<li>
								<a href="#" id="combine" data-action="combine">
									<i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
								</a>
							</li>
						<?php } ?>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('purchases')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . site_url('purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
	<?php if ($Owner || !$Admin) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
<?php }
?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("purchases"); ?>
                        <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no" '); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("supplier", "supplier"); ?>
                                <?php echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ""), 'class="form-control" id="supplier"'); ?> </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>

						<div class="col-sm-4">
                           <div class="form-group">
                                <?= lang("note", "note"); ?>
                                <?php echo form_input('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control tip" id="note"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
							<th><?php echo $this->lang->line("PR_No"); ?></th>
							<th><?php echo $this->lang->line("PO_No"); ?></th>
                            <th><?php echo $this->lang->line("ref_no"); ?></th>
                            <th><?php echo $this->lang->line("supplier"); ?></th>
							<th><?php echo $this->lang->line("status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("returns"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
                            <th></th>
                            <th>Created By</th>
                            <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("returns"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:100px; text-align: center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
