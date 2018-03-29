<!Doctype>
<html>
      <head>
	         <style>
			          @media print{
					     .table{
						       width:100%!important;  
							   margin:0px auto;
					    }
						.box-left{
							border: 1px solid black;
							float: left;
							height: 86px;
							width: 90%;
							margin-left:2%; !important;
					    }
						.box-right{
							border: 1px solid black;
							float: right;
							height: 86px;
							width: 90%; 
						}

					 }
			        .table{
						width:80%;
						margin:0px auto;
					}
					.box-left{
						border: 1px solid black;
						float: left;
						height: 86px;
						width: 90%;
						padding-left:5px;
					}
					 
					.box-right{
						border: 1px solid black;
						float: left;
						height: 60px;
						width: 100%;
						padding-left:5px;
					}
					.bg1{
						width:63%;
						float:left;
					}
					.bg{
						width:35%;
						float:left;
					}
			 
					
			 </style>
	  
	  </head>
	  <body>
	          <table class="table">
			          <thead>
					         <tr>
							      <td colspan="3"style="text-align:center"><h1><?=lang("Tea_tree_II")?></h1></td>
							 </tr>
					         <tr>
							      <td class="bg1">
							           <table class="box-left">
									           <tr>
											       <td width="15%"><?=lang("customer")?></td>
												   <td>:</td>
											   </tr>
											   <tr >
											       <td><?=lang("address")?></td>
												   <td>:</td>
											   </tr>
											   <tr >
											       <td><?=lang("Tel/Fax")?></td>
												   <td>:</td>
											   </tr>
									   </table> 				   
								  </td>
								  <td class="bg">
								       <table class="box-right">
									           <tr>
											       <td width="25%"><?=lang("date")?></td>
												   <td>:</td>
											   </tr>
											   <tr>
											       <td><?=lang("invoice_no")?></td>
												   <td>:</td>
											   </tr>
											  
									   </table> 
								  </td>
							 </tr>
					  </thead>
			  </table>
	  
	  </body>
</html>