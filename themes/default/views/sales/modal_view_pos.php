<?php
$statusCheck = $this->input->post("htest");
//$this->erp->print_arrays($statusCheck);
?>
<style>
    hr{
        border-color:#333;

    }
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <?php if($p->print == 1){?>
            <button type="button" class="btn btn-xs btn-default pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <?php } ?>
            <div class="text-center">
                <h1><?=lang('receipt')?></h1>
            </div>

            <!--
            <div class="well well-sm">
                <div class="row bold" style="font-size:12px;">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                        <?= lang("sale_status"); ?>: <?= lang($inv->sale_status); ?><br>
                        <?= lang("payment_status"); ?>: <?= lang($inv->payment_status); ?>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
						<p style="font-size:16px; margin:0 !important;"><?= lang("INVOICE"); ?></p>
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img height="45px" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
                        <img height="45px" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
-->
            <!--
            <div class="row" style="margin-bottom:15px;">
                <div class="col-xs-6">
                    <?php echo $this->lang->line("from"); ?>:
                    <h2 style="margin-top:10px;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                    <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                    <?php
            echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

            /* echo "<p>";

            if ($biller->cf1 != "-" && $biller->cf1 != "") {
                echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
            }
            if ($biller->cf2 != "-" && $biller->cf2 != "") {
                echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
            }
            if ($biller->cf3 != "-" && $biller->cf3 != "") {
                echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
            }
            if ($biller->cf4 != "-" && $biller->cf4 != "") {
                echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
            }
            if ($biller->cf5 != "-" && $biller->cf5 != "") {
                echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
            }
            if ($biller->cf6 != "-" && $biller->cf6 != "") {
                echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
            }

            echo "</p>"; */
            echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;
            ?>
                </div>
-->
            <div class="col-xs-12">
                <?php echo $this->lang->line("Received From"); ?>:<br/>
                <h2 style="margin-top:10px;"><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                <?php
                echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;

                echo "<p>";

                if ($customer->cf1 != "-" && $customer->cf1 != "") {
                    echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                }
                if ($customer->cf2 != "-" && $customer->cf2 != "") {
                    echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                }
                if ($customer->cf3 != "-" && $customer->cf3 != "") {
                    echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                }
                if ($customer->cf4 != "-" && $customer->cf4 != "") {
                    echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                }
                if ($customer->cf5 != "-" && $customer->cf5 != "") {
                    echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                }
                if ($customer->cf6 != "-" && $customer->cf6 != "") {
                    echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                }

                echo "</p>";
                echo lang("tel") . ": " . $customer->phone . "<br>" . lang("Being Payment Of") . ": " . $customer->email. "<br>" . lang("reference_no") . ": " . $inv->reference_no;

                ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped print-table order-table">

                <thead>

                <tr>
                    <th><?= lang("no"); ?></th>
                    <?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
                        <th><?= lang('product_code'); ?></th>
                    <?php } ?>
                    <th><?= lang("description"); ?></th>
                    <th><?= lang("unit"); ?></th>
                    <th><?= lang("quantity"); ?></th>
                    <th><?= lang("unit_price"); ?></th>
                    <?php if ($Settings->product_discount && $inv->product_discount != 0) { ?>
                        <th><?= lang("discount"); ?></th>
                    <?php } ?>
                    <th><?= lang("subtotal"); ?></th>
                </tr>

                </thead>

                <tbody>

                <?php $r = 1;
                $tax_summary = array();
                foreach ($rows as $row):
                    $free = lang('free');
                    $product_unit = '';
                    $total = 0;

                    if($row->variant){
                        $product_unit = $row->variant;
                    }else{
                        $product_unit = $row->uname;
                    }

                    $product_name_setting;
                    if($setting->show_code == 0) {
                        $product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
                    }else {
                        if($setting->separate_code == 0) {
                            $product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
                        }else {
                            $product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
                        }
                    }
                    ?>
                    <tr>
                        <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                        <?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
                            <td style="vertical-align:middle;">
                                <?= $row->product_code ?>
                            </td>
                        <?php } ?>
                        <td style="vertical-align:middle;">
                            <?= $product_name_setting ?>
                            <?= $row->details ? '<br>' . $row->details : ''; ?>
                            <?= $row->product_noted ? '<br>' . $row->product_noted : ''; ?>
                            <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                        </td>
                        <td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
                        <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                        <!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
                        <td style="text-align:right; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->net_unit_price):$free; ?></td>
                        <?php

                        if ($Settings->product_discount && $inv->product_discount != 0) {
                            echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                        }
                        ?>
                        <td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free;
                            $total += $row->subtotal;
                            ?></td>
                    </tr>
                    <?php
                    $r++;
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <?php
                $col = 4;
                if($setting->show_code == 1 && $setting->separate_code == 1) {
                    $col += 1;
                }
                if ($Settings->product_discount && $inv->product_discount != 0) {
                    $col++;
                }
                if ($Settings->tax1) {
                    $col++;
                }
                if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
                    $tcol = $col - 2;
                } elseif ($Settings->product_discount && $inv->product_discount != 0) {
                    $tcol = $col - 1;
                } elseif ($Settings->tax1) {
                    $tcol = $col - 1;
                } else {
                    $tcol = $col;
                }
                ?>
                <?php if ($inv->grand_total != $inv->total) { ?>
                    <tr>
                        <td colspan="<?= $tcol; ?>"
                            style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <?php
                        if ($Settings->tax1) {
                            echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                        }
                        if ($Settings->product_discount && $inv->product_discount != 0) {
                            echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                        }
                        ?>
                        <td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($return_sale && $return_sale->surcharge != 0) {
                    echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
                }
                ?>
                <?php if ($inv->order_discount != 0) {
                    echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                }
                ?>
                <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                    echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                }
                ?>
                <?php if ($inv->shipping != 0) {
                    echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                }
                ?>
                <tr>
                    <td colspan="<?= $col; ?>"
                        style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                        (<?= $default_currency->code; ?>)
                    </td>
                    <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                </tr>
                <tr>
                    <td colspan="<?= $col; ?>"
                        style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                        (<?= $default_currency->code; ?>)
                    </td>
                    <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
                </tr>
                <tr>
                    <td colspan="<?= $col; ?>"
                        style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                        (<?= $default_currency->code; ?>)
                    </td>
                    <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
                </tr>

                </tfoot>
            </table>
        </div>

        <div class="row">
            <div class="col-xs-1  pull-left"></div>
            <div class="col-xs-3  pull-left">
                <input type="checkbox" name="title" id="title">&nbsp&nbsp
                <label for="title">Title</label>

            </div>
            <div class="col-xs-3  pull-left">
                <input type="checkbox" name="cash" id="cash">&nbsp&nbsp
                <label for="cash">Cash</label>

            </div>
            <div class="col-xs-5">
                <input type="checkbox" name="check" id="check">&nbsp &nbsp
                <label for="check">CHQN _____________________</label>
                <p>* Payment is made once cheque is cleared only.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?php
                if ($inv->note || $inv->note != "") { ?>
                    <div class="well well-sm">
                        <p class="bold"><?= lang("note"); ?>:</p>
                        <div><?= $this->erp->decode_html($inv->note); ?></div>
                    </div>
                    <?php
                }
                if ($inv->staff_note || $inv->staff_note != "") { ?>
                    <div class="well well-sm staff_note">
                        <p class="bold"><?= lang("staff_note"); ?>:</p>
                        <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                    </div>
                <?php } ?>
            </div>
            <br/>

            <br/>

            <div class="row">
                <div class="clearfix"></div>
                <div class="col-xs-3  pull-left" style="text-align:center">
                    <hr/>
                    <p><?= lang("received_by"); ?>
                        <!--: <?= $biller->company != '-' ? $biller->company : $biller->name; ?> --></p>
                    <!--<p><?= lang("stamp_sign"); ?></p>-->
                </div>


            </div>
            <!--
                <div class="col-xs-5 pull-right no-print" >
                    <div class="well well-sm">
                        <p>
                            <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                            <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
                        </p>
                        <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
-->
        </div>
        <?php if (!$Supplier || !$Customer) { ?>
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                    <!--
						<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_tax_invoice') ?></span>
                            </a>
                        </div>
						-->

                    <?php if($p->print == 1){?>
                    <div class="btn-group" id ="title_link_invoice">
                        <a href="<?= site_url('sales/p_invoice/' . $inv->id) ?>" target="_blank" id ="temp_link_invoice" class="tip btn btn-primary" title="<?= lang('print_invoice') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('print_invoice') ?></span>
                        </a>
                    </div>
                    <div class="btn-group" id ="title_link_invoice_logo">
                        <a href="<?= site_url('sales/p_invoice_logo/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" id ="temp_link_invoice_logo" name="submit" title="<?= lang('Print_Invoice_Logo') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print_Invoice_Logo') ?></span>
                        </a>
                    </div>

                    <div class="btn-group"  id ="title_link_receipt">
                        <a href="<?= site_url('sales/p_invoice_receipt/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary"  id ="temp_link_receipt" title="<?= lang('Print_Receipt') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print_receipt') ?></span>
                        </a>
                    </div>

                    <div class="btn-group"  id ="title_link_receipt_logo">
                        <a href="<?= site_url('sales/p_receipt_logo/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" id ="temp_link_receipt_logo" title="<?= lang('Print_Receipt_Logo') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print_Receipt_Logo') ?></span>
                        </a>
                    </div>

                    <!--
						<div class="btn-group">
                            <a href="<?= site_url('sales/invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
							<a href="<?=base_url()?>sales/cabon_print/<?=$inv->id?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_cabon') ?>">
								<i class="fa fa-print"></i>
								<span class="hidden-sm hidden-xs"><?= lang('print_cabon') ?></span>
                            </a>
                        </div>
						-->
                    <div class="btn-group">
                        <a href="<?= site_url('sales/view/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                            <i class="fa fa-file-text-o"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice_devery/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_delivery') ?>">
                            <i class="fa fa-download"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_delivery') ?></span>
                        </a>
                    </div>
                    <?php } ?>

                    <?php if ($inv->attachment) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                            <i class="fa fa-envelope-o"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                            <i class="fa fa-download"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                        </a>
                    </div>
                    <?php if($inv->payment_status != 'paid') { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <!--
                        <div class="btn-group">
                            <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                data-html="true" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                            </a>
                        </div>
-->
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</div>
2
down vote


<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });

    var cashclick = 0;
    var checkclick = 0;
    var titleclick = 0;
    $('#cash').click(function() {
        if ($(this).is(':checked')) {
            cashclick = 1;
        }else{
            cashclick = 0;
        }
        var temp_link_receipt = $("#temp_link_receipt").attr('href');
        var temp_link_receipt_logo = $("#temp_link_receipt_logo").attr('href');
        var temp_link_invoice = $("#temp_link_invoice").attr('href');
        var temp_link_invoice_logo = $("#temp_link_invoice_logo").attr('href');

        var arr_receipt = temp_link_receipt.split("/");
        var arr_receipt_logo = temp_link_receipt_logo.split("/");
        var arr_invoice = temp_link_invoice.split("/");
        var arr_invoice_logo = temp_link_invoice_logo.split("/");

        temp_link_receipt = arr_receipt[0]+"/"+arr_receipt[1]+"/"+arr_receipt[2]+"/"+arr_receipt[3]+"/"+arr_receipt[4]+"/"+arr_receipt[5]+"/"+arr_receipt[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_receipt_logo = arr_receipt_logo[0]+"/"+arr_receipt_logo[1]+"/"+arr_receipt_logo[2]+"/"+arr_receipt_logo[3]+"/"+arr_receipt_logo[4]+"/"+arr_receipt_logo[5]+"/"+arr_receipt_logo[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_invoice = arr_invoice[0]+"/"+arr_invoice[1]+"/"+arr_invoice[2]+"/"+arr_invoice[3]+"/"+arr_invoice[4]+"/"+arr_invoice[5]+"/"+arr_invoice[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_invoice_logo = arr_invoice_logo[0]+"/"+arr_invoice_logo[1]+"/"+arr_invoice_logo[2]+"/"+arr_invoice_logo[3]+"/"+arr_invoice_logo[4]+"/"+arr_invoice_logo[5]+"/"+arr_invoice_logo[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;

        $("#title_link_receipt a").attr("href", temp_link_receipt);
        $("#title_link_receipt_logo a").attr("href", temp_link_receipt_logo);
        $("#title_link_invoice a").attr("href", temp_link_invoice);
        $("#title_link_invoice_logo a").attr("href", temp_link_invoice_logo);

    });

    $('#check').click(function() {
        if ($(this).is(':checked')) {
            checkclick = 1;
        }else{
            checkclick = 0;
        }
        var temp_link_receipt = $("#temp_link_receipt").attr('href');
        var temp_link_receipt_logo = $("#temp_link_receipt_logo").attr('href');
        var temp_link_invoice = $("#temp_link_invoice").attr('href');
        var temp_link_invoice_logo = $("#temp_link_invoice_logo").attr('href');

        var arr_receipt = temp_link_receipt.split("/");
        var arr_receipt_logo = temp_link_receipt_logo.split("/");
        var arr_invoice = temp_link_invoice.split("/");
        var arr_invoice_logo = temp_link_invoice_logo.split("/");

        temp_link_receipt = arr_receipt[0]+"/"+arr_receipt[1]+"/"+arr_receipt[2]+"/"+arr_receipt[3]+"/"+arr_receipt[4]+"/"+arr_receipt[5]+"/"+arr_receipt[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_receipt_logo = arr_receipt_logo[0]+"/"+arr_receipt_logo[1]+"/"+arr_receipt_logo[2]+"/"+arr_receipt_logo[3]+"/"+arr_receipt_logo[4]+"/"+arr_receipt_logo[5]+"/"+arr_receipt_logo[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_invoice = arr_invoice[0]+"/"+arr_invoice[1]+"/"+arr_invoice[2]+"/"+arr_invoice[3]+"/"+arr_invoice[4]+"/"+arr_invoice[5]+"/"+arr_invoice[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_invoice_logo = arr_invoice_logo[0]+"/"+arr_invoice_logo[1]+"/"+arr_invoice_logo[2]+"/"+arr_invoice_logo[3]+"/"+arr_invoice_logo[4]+"/"+arr_invoice_logo[5]+"/"+arr_invoice_logo[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;

        $("#title_link_receipt a").attr("href", temp_link_receipt);
        $("#title_link_receipt_logo a").attr("href", temp_link_receipt_logo);
        $("#title_link_invoice a").attr("href", temp_link_invoice);
        $("#title_link_invoice_logo a").attr("href", temp_link_invoice_logo);

    });

    $('#title').click(function() {
        if ($(this).is(':checked')) {
            titleclick = 1;
        }else{
            titleclick = 0;
        }
        var temp_link_receipt = $("#temp_link_receipt").attr('href');
        var temp_link_invoice = $("#temp_link_invoice").attr('href');
        var arr_receipt = temp_link_receipt.split("/");
        var arr_invoice = temp_link_invoice.split("/");
        temp_link_receipt = arr_receipt[0]+"/"+arr_receipt[1]+"/"+arr_receipt[2]+"/"+arr_receipt[3]+"/"+arr_receipt[4]+"/"+arr_receipt[5]+"/"+arr_receipt[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;
        temp_link_invoice = arr_invoice[0]+"/"+arr_invoice[1]+"/"+arr_invoice[2]+"/"+arr_invoice[3]+"/"+arr_invoice[4]+"/"+arr_invoice[5]+"/"+arr_invoice[6]+"/"+cashclick+"/"+checkclick+"/"+titleclick;

        $("#title_link_receipt a").attr("href", temp_link_receipt);
        $("#title_link_invoice a").attr("href", temp_link_invoice);
    });

</script>
