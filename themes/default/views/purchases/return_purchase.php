<style type="text/css">
	.select2-container {
		width: 100% !important;
	}
</style>
<script type="text/javascript">
    var count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>, po_edit = 1,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, shipping = 0, surcharge = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;

    $(document).ready(function () {
		var MaxInputs       = 30;
		var InputsWrapper   = jQuery(".journalContainer");
		var AddButton       = jQuery("#addDescription");
		
		var InputCount = jQuery(".journal-list");
		var x = InputCount.length;
		
		var FieldCount=2;

		$(AddButton).click(function (e)
		{     
			if(x <= MaxInputs) 
			{ 
				FieldCount++; 
				var div = '<div class="col-md-12 journal-list divwrap'+FieldCount+'">';
				div += '	<div class="col-md-6">';
				div += '			<div class="form-group company">';
				div += '				<select class="form-control input-tip select" id="select" name="account_section[]" style="width:100% !important;" required="required">';
				div += '				<?php foreach($sectionacc as $section){ ?>';
				div += '					<option value="<?=$section->accountcode?>"><?=$section->accountcode . " | " . $section->accountname; ?></option>';
				div += '				<?php } ?>';
				div += '				</select>';
				div += '			</div>';
				div += '		</div>';
				
				div += '		<div class="col-md-6">';
				div += '			<div class="form-group">';
				div += '				<input type="text" name="debit[]" value="" class="form-control debit'+FieldCount+'" id="debit"> ';
				div += '			</div>';
				div += '		</div>';
						
				div += '		<div class="col-md-1">';
				div += '			<button type="button" data="'+FieldCount+'" class="removefile btn btn-danger">&times;</button>';
				div += '		</div>';
				div += '	</div>';

				$(InputsWrapper).append(div);
				x++;
			}
			return false;
		});
		$(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            //localStorage.setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                //$('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if (p_val == 'gift_card') {
                $('.gc').show();
                $('.ngc').hide();
                $('#gift_card_no').focus();
            } else {
                $('.ngc').show();
                $('.gc').hide();
                $('#gc_details').html('');
            }
        });
		function AutoDebit() {
			var v_debit = 0;
			var i = 1;
			var expense_tax = 0;
			
			$('[name^=debit]').each(function(i, item) {
				v_debit +=  parseFloat($(item).val()) || 0;
			});
			
			if (site.settings.tax2 != 0) {
				if (potax2 = localStorage.getItem('potax2')) {
					$.each(tax_rates, function () {
						if (this.id == potax2) {
							if (this.type == 2) {
								expense_tax = parseFloat(this.rate);
							}
							if (this.type == 1) {
								expense_tax = parseFloat((v_debit * this.rate) / 100);
							}
						}
					});
				}
			}
			
			$("#calDebit").text(v_debit);
			$("#in_calDebit").val(v_debit);
			$('#total').text(formatPurDecimal(v_debit));
			$('#ttax2').text(formatPurDecimal(expense_tax));
			$('#in_calOrdTax').val(expense_tax);
			
			var v_grand_total = v_debit + expense_tax;
			$('#gtotal').text(formatPurDecimal(v_grand_total));
		}
        $('#extras-con').slideUp();
        <?php if ($inv) { ?>
        //localStorage.setItem('redate', '<?= $this->erp->hrld($inv->date) ?>');
        localStorage.setItem('reref', '<?= $reference ?>');
        localStorage.setItem('renote', '<?= str_replace(array("'", ""), "&#039",  $this->erp->decode_html($inv->note));?>');
        localStorage.setItem('reitems', JSON.stringify(<?= $inv_items; ?>));
        localStorage.setItem('rediscount', '<?= $inv->order_discount_id ?>');
        localStorage.setItem('retax2', '<?= $inv->order_tax_id ?>');
		localStorage.setItem('reshipping', '<?=$inv->shipping?>');
		localStorage.setItem('supplier_id', '<?=$inv->supplier_id?>');
        localStorage.setItem('return_surcharge', '0');
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('redate')) {
            $("#redate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#redate', function (e) {
            localStorage.setItem('redate', $(this).val());
        });
        if (redate = localStorage.getItem('redate')) {
            $('#redate').val(redate);
        }
        <?php } ?>
        if (reref = localStorage.getItem('reref')) {
            $('#reref').val(reref);
        }
		
        if (rediscount = localStorage.getItem('rediscount')) {
            $('#rediscount').val(rediscount);
        }
        if (retax2 = localStorage.getItem('retax2')) {
            $('#retax2').val(retax2);
        }
        if (return_surcharge = localStorage.getItem('return_surcharge')) {
            $('#return_surcharge').val(return_surcharge);
        }
		
        if (localStorage.getItem('reitems')) {
            loadItems();
        }
		
		/* ------------------------------
		 * Option change type paid
		 ------------------------------*/
		$(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            //localStorage.setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                //$('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if (p_val == 'gift_card') {
                $('.gc').show();
                $('.ngc').hide();
                $('#gift_card_no').focus();
            } else {
                $('.ngc').show();
                $('.gc').hide();
                $('#gc_details').html('');
            }
        });
		
		/* -----------------------
     * Edit Row Modal Hanlder 
     ----------------------- */
     $(document).on('click', '.edit', function () {
        var row = $(this).closest('tr');
        var row_id = row.attr('id');

        item_id = row.attr('data-item-id');
        item = reitems[item_id];
		if(row.children().children('.received').val() === NaN || row.children().children('.received').val() === undefined) {
			var qty = row.children().children('.rquantity').val(); 
		}else {
			var qty = row.children().children('.received').val();
		}
        var product_option = row.children().children('.roption').val(),
        unit_cost = formatPurDecimal(row.children().children('.realucost').val()),
        unit_variant_cost = formatPurDecimal(row.children().children('.unit_variant_cost').val()),
        discount = row.children().children('.rdiscount').val(),
        supplier = row.children().children('.rsupplier_id').val();
        var net_cost = unit_cost;
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
		var code = item.row.code;

        var results = [];
         $.ajax({
			type: "get",
			dataType: "json",
			async: false,
			url: site.base_url+"purchases/getSupplierProduct/",
			data: { code: code},
			success: function (data) {
                results = data;
			}
		});
        $('#psupplier').select2({
            data : results
        });
        $('#psupplier').select2('val', item.supplier_id);
		
        if (site.settings.tax1) {
            $('#ptax').select2('val', item.row.tax_rate);
            $('#old_tax').val(item.row.tax_rate);
            var item_discount = 0, ds = discount ? discount : '0';
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
                } else {
                    item_discount = parseFloat(ds/qty);
                }
            } else {
                item_discount = parseFloat(ds/qty);
            }
			
			net_cost = net_cost  - item_discount

            var pr_tax = item.row.tax_rate, pr_tax_val = 0;
            if (pr_tax !== null && pr_tax != 0) {
                $.each(tax_rates, function () {
                    if(this.id == pr_tax){
                        if (this.type == 1) {

                            if (reitems[item_id].row.tax_method == 0) {
                                pr_tax_val = formatPurDecimal(((unit_cost - item_discount) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                                pr_tax_rate = formatPurDecimal(this.rate) + '%';
                                net_cost -= pr_tax_val;
                            } else {
                                pr_tax_val = formatPurDecimal(((unit_cost - item_discount) * parseFloat(this.rate)) / 100);
                                pr_tax_rate = formatPurDecimal(this.rate) + '%';
                            }

                        } else if (this.type == 2) {

                            pr_tax_val = parseFloat(this.rate);
                            pr_tax_rate = this.rate;

                        }
                    }
                });
            }
        }
		
		if (site.settings.product_serial !== 0) {
            $('#pserial').val(row.children().children('.rserial').val());
        }
		
        var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
		if(site.settings.attributes == 1){
			if(item.options !== false) {
				var o = 1;
				opt = $("<select id=\"poption\" name=\"poption\" disabled = 'disabled' class=\"form-control select\" />");
				$.each(item.options, function () {
					if(o == 1) {
						if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
					}
					$("<option />", {value: this.id, text: this.name}).appendTo(opt);
					o++;
				});
			} 
		}

        $('#poptions-div').html(opt);
        $('select.select').select2({minimumResultsForSearch: 6});
        $('#pquantity').val(qty);
        $('#psupplier').val(psupplier);
        $('#old_qty').val(qty);
        $('#pcost').val(unit_variant_cost);
        $('#punit_cost').val(formatPurDecimal(parseFloat(unit_cost)+parseFloat(pr_tax_val)));
        $('#poption').select2('val', item.row.option);
        $('#old_cost').val(unit_cost);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pexpiry').val(row.children().children('.rexpiry').val());
        $('#pdiscount').val(discount);
        $('#net_cost').text(formatPurDecimal(unit_variant_cost));
        $('#pro_tax').text(formatPurDecimal(pr_tax_val));
        $('#prModal').appendTo("body").modal('show');

    });

		
        /* ------------------------------
         * Edit Row Quantity
         ------------------------------- */

        var old_row_qty;
        $(document).on("focus", '.rquantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.rquantity', function () {
            var row = $(this).closest('tr');
            var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
            if (!is_numeric(new_qty) || (new_qty > reitems[item_id].row.oqty)) {
                $(this).val(old_row_qty);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return false;
            }
            if(new_qty > reitems[item_id].row.oqty) {
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                $(this).val(old_row_qty);
                return false;
            }
            reitems[item_id].row.qty = new_qty;
            localStorage.setItem('reitems', JSON.stringify(reitems));
            loadItems();
        });
        var old_surcharge;
        $(document).on("focus", '#return_surcharge', function () {
            old_surcharge = $(this).val() ? $(this).val() : '0';
        }).on("change", '#return_surcharge', function () {
            var new_surcharge = $(this).val() ? $(this).val() : '0';
            if (!is_valid_discount(new_surcharge)) {
                $(this).val(new_surcharge);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return;
            }
            localStorage.setItem('return_surcharge', JSON.stringify(new_surcharge));
            loadItems();
        });
        $(document).on('click', '.redel', function () {
            var row = $(this).closest('tr');
            var item_id = row.attr('data-item-id');
            delete reitems[item_id];
            row.remove();
            if(reitems.hasOwnProperty(item_id)) { } else {
                localStorage.setItem('reitems', JSON.stringify(reitems));
                loadItems();
                return;
            }
        });
		if (localStorage.getItem('poextras')) {
			$('#extras').iCheck('check');
			$('#extras-con').show();
		}
		<?=isset($_POST['cf']) ? '$("#extras").iCheck("check");': '' ?>
		$('#extras').on('ifChecked', function () {
			localStorage.setItem('poextras', 1);
			$('#extras-con').slideDown();
		});
		$('#extras').on('ifUnchecked', function () {
			localStorage.removeItem("poextras");
			$('#extras-con').slideUp();
		});
    });
    
   /* $(document).on('click', '#add_return', function(){
        var purchase_id = <?php echo $inv->id ?>;
        var is = true;
        $.ajax({
            url: '<?php echo base_url() ?>purchases/checkReturn/' + purchase_id,
            async: false,
            success: function(data){
                if(data <= 0) {
                    bootbox.alert('<?= lang('purchase_already_return'); ?>'); 
                    is = false;
                    return false;
                }
            }
        });
        if(is == false){
            return false;
        }
    });*/
         
    //localStorage.clear();
    function loadItems() {

        if (localStorage.getItem('reitems')) {
            total = 0;
            count = 1;
            an = 1;
            product_tax = 0;
            invoice_tax = 0;
            product_discount = 0;
            order_discount = 0;
            total_discount = 0;
            $("#reTable tbody").empty();
			
            reitems = JSON.parse(localStorage.getItem('reitems'));
			
            $.each(reitems, function () {

                var item = this;
                var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
                reitems[item_id] = item;

                var product_id = item.row.id, item_type = item.row.type, combo_items = item.combo_items, item_cost = item.row.cost, item_qty = item.row.qty, item_oqty = item.row.oqty, purchase_item_id = item.row.purchase_item_id, item_bqty = item.row.quantity_balance, item_expiry = item.row.expiry, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
                var qty_received = (item.row.received >= 0) ? item.row.received : item.row.qty;
                var unit_variant_cost = item.unit_cost;
                var item_supplier_part_no = item.row.supplier_part_no ? item.row.supplier_part_no : '';
                if (item.row.new_entry === 1) { item_bqty = item_qty; }

                    var unit_cost = item.row.real_unit_cost;

                    var ds = item_ds ? item_ds : '0';
                    if (ds.indexOf("%") !== -1) {
                        var pds = ds.split("%");
                        if (!isNaN(pds[0])) {
                            item_discount = formatDecimal(parseFloat(((unit_cost) * parseFloat(pds[0])) / 100));
                        } else {
                            item_discount = formatDecimal(ds);
                        }
                    } else {
                         item_discount = parseFloat(ds);
                    }
                    product_discount += parseFloat(item_discount * item_qty);

                    unit_cost = formatDecimal(unit_cost-item_discount);
                    var pr_tax = item.tax_rate;
                    var pr_tax_val = 0, pr_tax_rate = 0;
                    if (site.settings.tax1 == 1) {
                        if (pr_tax !== false) {
                            if (pr_tax.type == 1) {

                                if (item_tax_method == '0') {
                                    pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)));
                                    pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                                } else {
                                    pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / 100);
                                    pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                                }

                            } else if (pr_tax.type == 2) {

                                pr_tax_val = parseFloat(pr_tax.rate);
                                pr_tax_rate = pr_tax.rate;

                            }
                            product_tax += pr_tax_val * item_qty;
                        }
                    }
                    item_cost = item_tax_method == 0 ? formatDecimal(unit_cost-pr_tax_val) : formatDecimal(unit_cost);
                    unit_cost = formatDecimal(unit_cost+item_discount);
                    var sel_opt = '';
                    $.each(item.options, function () {
                        if(this.id == item_option) {
                            sel_opt = this.name;
                        }
                    });

                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
                tr_html = '<td><span class="text-center">#'+ an +'</td><td><input name="purchase_item_id[]" type="hidden" class="rpiid" value="' + purchase_item_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="part_no[]" type="hidden" class="rpart_no" value="' + item_supplier_part_no + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+' <span class="label label-default">'+item_supplier_part_no+'</span></span><i class="pull-right fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
                if (site.settings.product_expiry == 1) {
                    tr_html += '<td><input class="form-control date rexpiry"readonly name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
                }
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + item_cost + '"><input class="rucost" name="unit_cost[]" type="hidden" value="' + unit_cost + '"><input class="realucost" name="real_unit_cost[]" type="hidden" value="' + item.row.real_unit_cost + '"><input class="unit_variant_cost" name="unit_variant_cost[]" type="hidden" value="' + unit_variant_cost + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(unit_variant_cost) + '</span></td>';
                tr_html += '<td class="text-center"><span>'+formatDecimal(item_oqty)+'</span></td>';
                if (po_edit) {
                    tr_html += '<td class="text-center"><span>'+formatDecimal(qty_received)+'</span></td>';
                }
                tr_html += '<td><input class="form-control text-center rquantity" readonly name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0 - (item_discount * item_qty)) + '</span></td>';
                }
                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(unit_variant_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip redel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#reTable");
                //total += parseFloat(item_cost * item_qty);
                total += formatDecimal(((unit_variant_cost + pr_tax_val) * item_qty));
                count += parseFloat(item_qty);
                an++;
            });
			
            // Order level discount calculations
            if (podiscount = localStorage.getItem('rediscount')) {
                var ds = podiscount;
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        order_discount = ((total) * parseFloat(pds[0])) / 100;
                    } else {
                        order_discount = ((total) * parseFloat(ds)) / 100;
                    }
                } else {
                    order_discount = ((total) * parseFloat(ds)) / 100;
                }
            }

            // Order level discount calculations
            if (reshipping = localStorage.getItem('reshipping')) {
                shipping = parseFloat(reshipping);
            }

            // Order level tax calculations
            if (site.settings.tax2 != 0) {
                if (potax2 = localStorage.getItem('retax2')) {
                    $.each(tax_rates, function () {
                        if (this.id == potax2) {
                            if (this.type == 2) {
                                invoice_tax = parseFloat(this.rate);
                            }
                            if (this.type == 1) {
                                invoice_tax = parseFloat(((total - order_discount + shipping) * this.rate) / 100);
                            }
                        }
                    });
                }
            }
			if (parseFloat(localStorage.getItem('retax2')) >= 1 || localStorage.getItem('rediscount').length >= 1 || parseFloat(localStorage.getItem('reshipping')) >= 1) {
				localStorage.setItem('poextras', '1');
			}
			
            total_discount = parseFloat(order_discount + product_discount);
            // Totals calculations after item addition
            var gtotal = parseFloat(((total + invoice_tax) - order_discount + shipping));
			
            if (return_surcharge = localStorage.getItem('return_surcharge')) {
                var rs = return_surcharge.replace(/"/g, '');
                if (rs.indexOf("%") !== -1) {
                    var prs = rs.split('%');
                    var percentage = parseFloat(prs[0]);
                    if (!isNaN(prs[0])) {
                        surcharge = parseFloat((gtotal * percentage) / 100);
                    } else {
                        surcharge = parseFloat(rs);
                    }
                } else {
                    surcharge = parseFloat(rs);
                }
            }
            
            gtotal -= surcharge;

            $('#total').text(formatMoney(total));
            $('#titems').text((an - 1) + ' (' + (parseFloat(count) - 1) + ')');
			$('#tdis').text(formatMoney(order_discount));
			$('#shipping').text(formatMoney(shipping));
            $('#total_items').val((parseFloat(count) - 1));
            $('#trs').text(formatMoney(surcharge));
            if (site.settings.tax1) {
                $('#ttax1').text(formatMoney(product_tax));
            }
            if (site.settings.tax2 != 0) {
                $('#ttax2').text(formatMoney(invoice_tax));
            }
            $('#gtotal').text(formatMoney(gtotal));

        }
		$("#reref").attr('readonly', true); 
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			$("#reref").attr('readonly', false); 
			$("#reref").val("");
		  }else{
			$("#reref").attr('readonly', true); 
			var temp = $("#purchase_return").val();
			$("#reref").val(temp);
			
		  }
		});
        $("#rreference").attr('readonly', true); 
        $('#rref_st').on('ifChanged', function() {
          if ($(this).is(':checked')) {
            $("#rreference").attr('readonly', false); 
            $("#rreference").val("");
          }else{
            $("#rreference").attr('readonly', true); 
            var temp = $("#rreference_temp").val();
            $("#rreference").val(temp);
            
          }
        });
    }
	
	$(window).load(function(){
		var type_of_po = '<?= $type_of_po ?>';
		if(type_of_po == 'po'){
			$(".item_form").css("display", "");
			$(".po_form").css("display", "none");
			$(".exp_form").css("display", "none");
		}else{
			$(".item_form").css("display", "none");
			$(".po_form").css("display", "");
			$(".exp_form").css("display", "");
		}
	});
		
</script>
<div class="row">
	<div class="col-md-12">
		<?php
		if ($inv->payment_status == 'paid' || $inv->payment_status == 'partial') {
			echo '<div class="alert alert-success"><p>' . lang('payment_status') . ': <strong>' . $inv->payment_status . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->erp->formatMoney($inv->paid) . '</strong></p></div>';
		} else {
			echo '<div class="alert alert-warning"><p>' . lang('payment_status') . ': <strong>' . $inv->payment_status . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->erp->formatMoney($inv->paid) . '</strong></p></div>';
		}
		?>
		<input type="hidden" name="total_pay" value="<?php echo $this->erp->formatMoney($inv->paid) ? $this->erp->formatMoney($inv->paid) : 0 ?>">
	</div>
</div>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-minus-circle"></i><?= lang("return_purchases"); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang("enter_info"); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-resl-form');
                echo form_open_multipart("purchases/return_purchase/" . $inv->id, $attrib)
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "redate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="redate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        
						<div class="col-md-4">
							<?= lang("reference_no", "reref"); ?>
							<div style="float:left;width:100%;">
								<div class="form-group">
									<div class="input-group">  
											<?php echo form_input('reference_no',($reference ? $reference : ''),'class="form-control input-tip" id="reref"'); ?>
											<input type="hidden"  name="purchase_return"  id="purchase_return" value="<?=$reference ? $reference : '' ?>" />
										<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
											<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
										</div>
									</div>
								</div>
							</div>
                        </div>
						
						<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("purchase_invoice", "pref"); ?>
                                <?php echo form_input('purchase_ref', ($referenceno->reference_no ? $referenceno->reference_no : ''), 'class="form-control input-tip" id="purchase_ref" style="pointer-events: none;"'); ?>
                            </div>
                        </div>
						
						<?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("project", "slbiller"); ?>
                                    <?php
                                    $bl[""] = "";
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ($inv->biller_id ? $inv->biller_id : $Settings->default_biller)), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%; pointer-events: none;"');
                                    ?>
                                </div>
                            </div>
                        <?php } else {
                            $biller_input = array(
                                'type' => 'hidden',
                                'name' => 'biller',
                                'id' => 'slbiller',
                                'value' => $inv->biller_id,
								'style' => 'pointer-events: none;'
                            );

                            echo form_input($biller_input);
                        } ?>
						<div class="col-md-4 powarehouse_o" >
                            <div class="form-group">
                                <?= lang("warehouse", "powarehouse"); ?>
                                <?php
									$q = $this->db->get_where('erp_purchases',array('id'=>$id),1);
									$q2 = $this->db->get_where('erp_warehouses',array('id'=>$q->row()->warehouse_id),1);
								  echo form_input('warehouse', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $q2->row()->name), 'class="form-control input-tip" id="warehouse" required="required" style="pointer-events: none;"'); 
                               
                                ?>
                            </div>
                        </div>
                        
						<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("return_surcharge", "return_surcharge"); ?>
                                <?php echo form_input('return_surcharge', (isset($_POST['return_surcharge']) ? $_POST['return_surcharge'] : 0), 'class="form-control input-tip" id="return_surcharge" required="required"'); ?>
                            </div>
                        </div>
						
						<div class="col-md-4 po_form">
                            <div class="form-group">
                                <?= lang("customers", "scustomer"); ?>
								<input type="text" value="<?= $customer->name;?>" class="form-control" style="pointer-events: none;" />
								<input name="customers" type="hidden" value="<?= $inv->customer_id;?>" class="form-control" style="pointer-events: none;" />
                            </div>
                        </div>
						
						<div class="col-md-4 po_form">
                            <div class="form-group">
                                <?= lang("customer_invoices", "scustomer_no"); ?>
								<input name="customer_invoices" type="text" value="<?= $sale->reference_no;?>" class="form-control" style="pointer-events: none;" />
								<input name="customer_invoices" type="hidden" value="<?= $inv->sale_id;?>" class="form-control" style="pointer-events: none;" />
                            </div>
                        </div>
						
						<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("supplier", "supplier"); ?>
								<input type="text" value="<?= $inv->supplier;?>" class="form-control" style="pointer-events: none;" />
                            </div>
                        </div>

                        <div class="col-md-12 item_form">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?></label> (<?= lang('return_tip'); ?>)

                                <div class="controls table-controls">
                                    <table id="reTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
										    <th><?= lang("no"); ?></th>
                                            <th class="col-md-4"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                            <?php
                                            if ($Settings->product_expiry) {
                                                echo '<th class="col-md-1">' . $this->lang->line("expiry_date") . '</th>';
                                            }
                                            ?>
                                            <th class="col-md-1"><?= lang("unit_cost"); ?></th>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <th class="col-md-1"><?= lang("received"); ?></th>
                                            <th class="col-md-1"><?= lang("return_quantity"); ?></th>
                                            <?php
                                            if ($Settings->product_discount) {
                                                echo '<th class="col-md-1">' . $this->lang->line("discount") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->tax1) {
                                                echo '<th class="col-md-1">' . $this->lang->line("product_tax") . '</th>';
                                            }
                                            ?>
                                            <th><?= lang("subtotal"); ?></th>
                                            <th style="width: 30px !important; text-align: center;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>

						<!-- <div class="col-md-12 item_form">
                            <div class="form-group">
                                <input type="checkbox" class="checkbox" id="extras" value=""/>
                                <label for="extras" class="padding05"><?= lang('more_options') ?></label>
                            </div>
                            <div class="row" id="extras-con" style="display: none;">
                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("discount_label", "podiscount"); ?>
                                        <?php echo form_input('discount_label', $inv->order_discount_id, 'class="form-control input-tip" id="podiscount" style="pointer-events: none;"'); ?>
                                        <input type="hidden" name="order_discount"​     value="<?= $inv->order_discount_id;?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" style="margin-bottom:5px;">
                                        <?= lang("shipping", "poshipping"); ?>
                                        <?php echo form_input('shipping', number_format($inv->shipping), 'class="form-control input-tip" id="poshipping" style="pointer-events: none;"'); ?>
                                        <input type="hidden" name="shipping"  value="<?= $inv->shipping;?>">
                                    </div>
                                </div>
								
								<?php if ($Settings->tax1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang('order_tax', 'potax2') ?>
                                            <?php
                                            $tr[""] = "";
                                            foreach ($tax_rates as $tax) {
                                                $tr[$tax->id] = $tax->name;
                                            }
                                            echo form_dropdown('order_tax', $tr, $inv->order_tax_id, 'id="potax2" class="form-control input-tip select"   style="width:100%;pointer-events: none;"');
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
								
                            </div>

                        </div> -->
						
                        <div style="height:15px; clear: both;"></div>
                        
						<?php if($inv->paid > 0) { ?>
							<div class="item_form" id="payments">
								<div class="col-md-12">
									<div class="well well-sm well_1">
										<div class="col-md-12">
											<div class="row">
												<div class="col-sm-12">
													<div class="col-md-4">
														<div class="form-group">
															<?= lang("payment_reference_no", "payment_reference_no"); ?>
															<?= form_input('payment_ref', $payment_ref, 'class="form-control tip" id="payment_ref" style="pointer-events: none;" required="required"'); ?>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="payment">
															<div class="form-group">
																<?= lang("amount", "amount_1"); ?>
																<input name="amount-paid" type="text" id="amount_1" value="<?= $inv->paid;?>" class="pa form-control kb-pad amount" style="pointer-events: none;"/>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<?= lang("paying_by", "paid_by_1"); ?>
															<select name="paid_by" id="paid_by_1" style="pointer-events: none;" class="form-control paid_by">
																<option value="cash"><?= lang("cash"); ?></option>
																<option value="gift_card"><?= lang("gift_card"); ?></option>
																<option value="Cheque"><?= lang("cheque"); ?></option>
																<option value="other"><?= lang("other"); ?></option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-12">
													<div class="col-sm-4" id="bank_acc">
														<div class="form-group">
															<?= lang("bank_account", "bank_account_1"); ?>
															<?php $bank = array('0' => '-- Select Bank Account --');
															foreach($bankAccounts as $bankAcc) {
																$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
															}
															echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required"');
															?>
														</div>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="pcc_1" style="display:none;">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<input name="pcc_no" type="text" id="pcc_no_1"
																   class="form-control" placeholder="<?= lang('cc_no') ?>"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">

															<input name="pcc_holder" type="text" id="pcc_holder_1"
																   class="form-control"
																   placeholder="<?= lang('cc_holder') ?>"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<select name="pcc_type" id="pcc_type_1"
																	class="form-control pcc_type"
																	placeholder="<?= lang('card_type') ?>">
																<option value="Visa"><?= lang("Visa"); ?></option>
																<option
																	value="MasterCard"><?= lang("MasterCard"); ?></option>
																<option value="Amex"><?= lang("Amex"); ?></option>
																<option value="Discover"><?= lang("Discover"); ?></option>
															</select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<input name="pcc_month" type="text" id="pcc_month_1"
																   class="form-control" placeholder="<?= lang('month') ?>"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">

															<input name="pcc_year" type="text" id="pcc_year_1"
																   class="form-control" placeholder="<?= lang('year') ?>"/>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">

															<input name="pcc_ccv" type="text" id="pcc_cvv2_1"
																   class="form-control" placeholder="<?= lang('cvv2') ?>"/>
														</div>
													</div>
												</div>
											</div>
											<div class="pcheque_1" style="display:none;">
												<div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
													<input name="cheque_no" type="text" id="cheque_no_1"
														   class="form-control cheque_no"/>
												</div>
											</div>
											<div class="gc" style="display: none;">
												<div class="form-group">
													<?= lang("gift_card_no", "gift_card_no"); ?>
													<input name="gift_card_no" type="text" id="gift_card_no" class="pa form-control kb-pad" style="width:100%;"/>
												</div>
												<div id="gc_details"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						<?php } ?>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
                        <input type="hidden" name="order_tax" value="" id="retax2" required="required"/>
                        <input type="hidden" name="discount" value="" id="rediscount" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-md-8">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("return_note", "renote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="renote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" journalContainer">
                            <div class="col-md-4">
                                        <?= lang("chart_account", "chart_account"); ?>
                                    </div>
                                     <div class="col-md-4">
                                        <?= lang("amount", "amount"); ?>
                                    </div>
                                <div class="row">
                                   
                                <?php
                                $n = 1;
                                $debit = 0;
                                $credit = 0;
                                foreach($journals as $journal){
                                    
                                    if($journal->debit != 0 && $acc_setting->default_purchase_tax != $journal->account_code){
                                    
                                ?>
                                    <div class="col-md-12 journal-list">
                                        <div class="col-md-4">
                                            <div class="form-group company">
                                                <?php
                                                    $acc_section = array(""=>"");
                                                    foreach($sectionacc as $section){
                                                        $acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
                                                    }
                                                    echo form_dropdown('account_section[]', $acc_section, $journal->account_code,'', 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width: 100% !important; pointer-events: none !important;"'); 
                                                    ?>
                                                <input type="hidden" name="tran_id[]" value="<?= $journal->tran_id ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo form_input('debit[]', ($journal->debit!=0?$journal->debit:$journal->credit), 'class="form-control debit" id="debit debit'.$n.'" style="pointer-events: none !important;"'); ?>
                                            </div>
                                        </div>
                                        
                                        <!--
                                        <div class="col-md-1">
                                            <div class="form-group ">
                                                <button type="button" class="removefiles btn btn-danger">&times;</button>
                                            </div>
                                        </div> -->
                                    </div>
                                    
                                    <?php 
                                    $debit += $journal->debit;
                                    $n++;
                                    }
                                }
                                    ?>
                                </div>
                            </div>
                                    
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="calDebit" style="padding-left: 18px;"><?=$debit?></label>
                                        <input type="hidden" id="in_calDebit" value="<?=$debit?>"  class="in_calDebit" name="in_calDebit" />
                                        <input type="hidden" id="in_calOrdTax"  class="in_calOrdTax" name="in_calOrdTax" />
                                    </div>
                                </div>
						
						<div class="clearfix"></div>
                        <div class="col-md-12 ">
                        <div class="form-group">
                            <input type="checkbox" class="checkbox" id="extras" checked value=""/>
                            <label for="extras" class="padding05"><?= lang('more_options') ?></label>
                        </div>
                        <div class="row" id="extras-con" style="display: none;">
                            
                            <?php if ($Settings->tax1) { ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang('order_tax', 'potax2') ?>
                                        <?php
                                        $tr[""] = "";
                                        foreach ($tax_rates as $tax) {
                                            $tr[$tax->id] = $tax->name; 
                                        }
                                        echo form_dropdown('order_tax', $tr, "", 'id="potax2" class="form-control input-tip select" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("payment_term", "slpayment_term"); ?>
                                    <?php
                                        $ptr[""] = "";
                                        foreach ($payment_term as $term) {
                                            $ptr[$term->id] = $term->description;
                                        }
                                        echo form_dropdown('payment_term', $ptr,$sale_order->payment_term?$sale_order->payment_term:"", 'id="slpayment_term" data-placeholder="' . lang("payment_term_tip") .  '" class="form-control input-tip select" style="width:100%;"');
                                        //echo form_input('payment_term',$ptr,'11', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>
                                </div>
                            </div>
                            
                        </div>

                    </div>
						<div class="exp_form">							
							<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
							echo form_open_multipart("account/save_journal", $attrib); ?>
							<div class="panel-body">
							<div class="row">
								<?php
								$description = '';
								if(isset($journals)){
									$old_transno=0;
									foreach($journals as $journal1){
										$old_transno = $journal1->tran_no;
										if($journal1->description != ""){
										
											$description = $journal1->description;
										}
									}
								}
								?>	
								<!--
								<div class="col-md-8"></div>								
								<div class="col-md-12">
									<div class="form-group">
										<button style="margin-right: 30px;" type="button" class="btn btn-primary pull-right" id="addDescription"><i class="fa fa-plus-circle"></i></button>
									</div>
								</div> -->
							</div>

                            <?php if($inv->paid > 0) { ?>
							<div class=" journalContainer well well-sm well_1">
                                <div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
							
									</div>
									<input type="hidden" name="old_transno" value="<?=$old_transno?>">
								</div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <?= lang("reference_no", "rreference"); ?>
                                        <div style="float:left;width:100%;">
                                            <div class="form-group">
                                                <div class="input-group">  
                                                    <?php echo form_input('rreference', $rreference?$rreference:"",'class="form-control input-tip" id="rreference"'); ?>
                                                    <input type="hidden"  name="rreference_temp"  id="rreference_temp" value="<?= $rreference?$rreference:"" ?>" />
                                                    <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                                        <input type="checkbox" name="ref_status" id="rref_st" value="1" style="margin-top:3px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?= lang("amount", "amount"); ?>
                                        <div class="form-group">
                                            <?php echo form_input('amount', ($inv->paid!=0?$inv->paid:0), 'class="form-control debit" id="debit debit'.$n.'" style="pointer-events: none !important;"'); ?>
                                        </div>
                                    </div>
									<div class="col-sm-4" id="bank_acc">
                                        <div class="form-group">
                                            <?= lang("bank_account", "bank_account_1"); ?>
                                            <?php $bank = array('0' => '-- Select Bank Account --');
                                            foreach($bankAccounts as $bankAcc) {
                                                $bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
                                            }
                                            echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required"');
                                            ?>
                                        </div>
                                    </div>
									<div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("paying_by", "paid_by_1"); ?>
                                            <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                                <option value="cash"><?= lang("cash"); ?></option>
                                                <option value="gift_card"><?= lang("gift_card"); ?></option>
                                                <option value="Cheque"><?= lang("cheque"); ?></option>
                                                <option value="other"><?= lang("other"); ?></option>
                                            </select>
                                        </div>
                                    </div>	
                                    <div class="col-md-12">
                                        <div class="clearfix"></div>
                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?= lang('cc_holder') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?= lang('card_type') ?>">
                                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                                            <option
                                                                value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                                        </select>
                                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?= lang('month') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?= lang('year') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?= lang('cvv2') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="gc" style="display: none;">
                                            <div class="form-group">
                                                <?= lang("gift_card_no", "gift_card_no"); ?>
                                                <div class="input-group">

                                                    <input name="gift_card_no" type="text" id="gift_card_no"
                                                           class="pa form-control kb-pad"/>

                                                    <div class="input-group-addon"
                                                         style="padding-left: 10px; padding-right: 10px; height:25px;">
                                                        <a href="#" id="sellGiftCard" class="tip"
                                                           title="<?= lang('sell_gift_card') ?>"><i
                                                                class="fa fa-credit-card"></i></a></div>
                                                </div>
                                            </div>
                                            <div id="gc_details"></div>
                                        </div>
                                    </div>
								</div>
                                        
									

								</div>
							</div>
                            <?php } ?>
							</div>
						</div>	

                        <div class="col-md-12">
                            <div class="fprom-group"><?php echo form_submit('add_return', $this->lang->line("submit"), 'id="add_return" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
							
							<div id="bottom-total" class="well well-sm item_form" style="margin-bottom: 0;">
                                <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                                    <tr class="warning">
                                        <td>
                                            <?= lang('items') ?>
                                            <span class="totals_val pull-right" id="titems">0</span>
                                        </td>
                                        <td>
                                            <?= lang('total') ?>
                                            <span class="totals_val pull-right" id="total">0.00</span>
                                        </td>
										<td>
                                            <?= lang('order_discount') ?>
                                            <span class="totals_val pull-right" id="tdis">0.00</span>
                                        </td>
										<td>
                                            <?= lang('shipping') ?>
                                            <span class="totals_val pull-right" id="shipping">0.00</span>
                                        </td>
										<?php if ($Settings->tax2) { ?>
                                        <td>
                                            <?= lang('order_tax') ?>
                                            <span class="totals_val pull-right" id="ttax2">0.00</span>
                                        </td>
                                        <?php } ?>
										<td>
                                            <?= lang('surcharges') ?>
                                            <span class="totals_val pull-right" id="trs">0.00</span>
                                        </td>
                                        <td>
                                            <?= lang('grand_total') ?>
                                            <span class="totals_val pull-right" id="gtotal">0.00</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>							
                        </div>
                    </div>
                </div>
				

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
<div class="modal item_form" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
							class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
				<h4 class="modal-title" id="prModalLabel"></h4>
			</div>
			<div class="modal-body" id="pr_popover_content">
				<form class="form-horizontal" role="form">
					<div class="form-group" style="display:none;">
						<label class="col-sm-4 control-label"><?= lang('supplier_products') ?></label>
						<div class="col-sm-8">
							<input type="hidden" name="psupplier[]" value="" id="psupplier"class="form-control" style="width:100%;" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>">
						</div>
					</div>
					<?php if ($Settings->tax1) { ?>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
							<div class="col-sm-8">
								<?php
								$tr[""] = "";
								foreach ($tax_rates as $tax) {
									$tr[$tax->id] = $tax->name;
								}
								echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" disabled ="disabled" style="width:100%;"');
								?>
							</div>
						</div>
					<?php } ?>
					<?php if ($Settings->purchase_serial) { ?>
						<div class="form-group">
							<label for="serial_no" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

							<div class="col-sm-8" id="serial"></div>
						</div>
					<?php } ?>
					<div class="form-group">
						<label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

						<div class="col-sm-8">
							<input type="text" class="form-control" id="pquantity" readonly>
						</div>
					</div>
					<?php if ($Settings->product_expiry) { ?>
						<div class="form-group">
							<label for="pexpiry" class="col-sm-4 control-label"><?= lang('product_expiry') ?></label>

							<div class="col-sm-8">
								<input type="text" class="form-control date" id="pexpiry" readonly>
							</div>
						</div>
					<?php } ?>
					<div class="form-group">
						<label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

						<div class="col-sm-8">
							<div id="poptions-div"></div>
						</div>
					</div>
					<?php if ($Settings->product_discount) { ?>
						<div class="form-group">
							<label for="pdiscount"
								   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

							<div class="col-sm-8">
								<input type="text" class="form-control" id="pdiscount" readonly>
							</div>
						</div>
					<?php } ?>
					<?php if($Owner || $Admin || $GP['purchases-cost']) {?>
					<div class="form-group">
						<label for="pcost" class="col-sm-4 control-label"><?= lang('unit_cost') ?></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="pcost" readonly>
						</div>
					</div>
					<?php } ?>
					<table class="table table-bordered table-striped">
						<tr>
							<?php if($Owner || $Admin || $GP['purchases-cost']) {?>
							<th style="width:25%;"><?= lang('unit_cost'); ?></th>
							<th style="width:25%;"><span id="net_cost"></span></th>
							<?php } ?>
							<th style="width:25%;"><?= lang('product_tax'); ?></th>
							<th style="width:25%;"><span id="pro_tax"></span></th>
						</tr>
					</table>
					<input type="hidden" id="punit_cost" value=""/>
					<input type="hidden" id="old_tax" value=""/>
					<input type="hidden" id="old_qty" value=""/>
					<input type="hidden" id="old_cost" value=""/>
					<input type="hidden" id="row_id" value=""/>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
			</div>
		</div>
	</div>
</div>
