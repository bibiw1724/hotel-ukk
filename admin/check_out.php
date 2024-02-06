<?php include('db_connect.php');
$cat = $conn->query("SELECT * FROM room_categories");
$cat_arr = array();
while ($row = $cat->fetch_assoc()) {
	$cat_arr[$row['id']] = $row;
}
$room = $conn->query("SELECT * FROM rooms");
$room_arr = array();
while ($row = $room->fetch_assoc()) {
	$room_arr[$row['id']] = $row;
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<th>#</th>
								<th>Category</th>
								<th>Room</th>
								<th>Reference</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$checked = $conn->query("SELECT * FROM checked where status != 0 order by status desc, id asc ");
								while ($row = $checked->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="text-center">
											<?php
											$room_id = $row['room_id'];

											// Pastikan 'room_id' ada di dalam $room_arr sebelum mengaksesnya
											if (isset($room_arr[$room_id]) && isset($room_arr[$room_id]['category_id'])) {
												$category_id = $room_arr[$room_id]['category_id'];

												// Pastikan 'category_id' ada di dalam $cat_arr sebelum mengaksesnya
												if (isset($cat_arr[$category_id]) && isset($cat_arr[$category_id]['name'])) {
													echo $cat_arr[$category_id]['name'];
												} else {
													echo "Category name not available";
												}
											} else {
												echo "Room information not available";
											}
											?>
										</td>

										<td class=""> <?php
														$room_id = $row['room_id'];

														// Pastikan 'room_id' ada di dalam $room_arr sebelum mengaksesnya
														if (isset($room_arr[$room_id]) && isset($room_arr[$room_id]['room'])) {
															echo $room_arr[$room_id]['room'];
														} else {
															echo "Room information not available";
														}
														?></td>
										<td class=""><?php echo $row['ref_no'] ?></td>
										<?php if ($row['status'] == 1) : ?>
											<td class="text-center"><span class="badge badge-warning">Checked-IN</span></td>
										<?php else : ?>
											<td class="text-center"><span class="badge badge-success">Checked-Out</span></td>
										<?php endif; ?>
										<td class="text-center">
											<button class="btn btn-sm btn-primary check_out" type="button" data-id="<?php echo $row['id'] ?>">View</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('table').dataTable()
	$('.check_out').click(function() {
		uni_modal("Check Out", "manage_check_out.php?checkout=1&id=" + $(this).attr("data-id"))
	})
	$('#filter').submit(function(e) {
		e.preventDefault()
		location.replace('index.php?page=check_in&category_id=' + $(this).find('[name="category_id"]').val() + '&status=' + $(this).find('[name="status"]').val())
	})
</script>