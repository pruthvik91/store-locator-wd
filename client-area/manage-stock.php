<?php
include "../config.php";
include('./header.php');

$price_decimal_value = 2;

$result = $db->prepare("SELECT 
            product_name,hsn_sac,pur_price,sell_price,sum(item_available) AS item_available
        FROM 
            store_db_0_5k.`store_product` 
        WHERE 
        		is_deleted != 1 
        GROUP BY 
            product_name 
        ORDER BY 
            item_available DESC 
        ");

$result->execute();
$rows = $result->fetchAll(PDO::FETCH_OBJ);
$rowcount = count($rows);

?>
<style>
	table.dataTable tbody th, table.dataTable tbody td {
		padding: 0.875rem 0.9375rem;
    vertical-align: top;
    border-top: 1px solid #e8ebf1;
	}
	th {
    color: black !important;
}
</style>

<div class="row">
	<div class="span12">
		<form name="tabledata" method="post">
			<div class="widget widget-table action-table">

				<div class="widget-content">
					<table class="table wrap_table manage-stock-table  footable footable-1 breakpoint-lg" data-footable="" data-toggle-column="last" style="display: table;">
						<thead>
							<tr class="">
								<th class="" style="display: table-cell;">Name</th>
								<th data-breakpoints="md" style="display: table-cell;">HSN Code</th>
								<th data-breakpoints="sm" class="text-right" style="display: table-cell;">Purchase Price</th>
								<th data-breakpoints="sm" class="text-right" style="display: table-cell;">Sell Price</th>
								<th style="display: table-cell;" class="text-right">Current Stock</th>
							</tr>
						</thead>
						<tbody>
<?php 
if($rowcount> 0 )
{
	foreach($rows as $rs)
    {
?>

							<tr>
								<td class="footable-first-visible" style="display: table-cell;"> <?php echo $rs->product_name ?></td>
								<td style="display: table-cell;"><?php echo $rs->hsn_sac ?> </td>
								<td class="text-right" style="display: table-cell;"> <?php echo $rs->pur_price ?></td>
								<td class="text-right" style="display: table-cell;"> <?php echo $rs->sell_price ?> </td>
								<td  class="text-right" id="qunatity_1" style="display: table-cell;"> <?php echo $rs->item_available ?> </td>
							</tr>
							<?php
						}					}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-actions">
				<a href="list-products.php" class="btn btn_white"><i class="fa fa-chevron-left"></i>Back</a>				
			</div>


		</form>
	</div>
</div>

</div>

<script>
    $(document).ready(function() {
        $('.manage-stock-table').DataTable({
            "paging": true, 
            "searching": true, 
            "ordering": true, 
            "info": true, 
            "lengthMenu": [10, 25, 50, 75, 100], 
        });
    });
</script>

<?php
include('footer.php');
?>