<!-- nav_body -->
<div class="col-lg-10 col-md-10 col-sm-10" style="margin-left:300px;margin-right:30px">
	

	<div class="row">
		<div class="col-lg-10">
			<h2>Exercise list for all chapters.</h2>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>NO.</th>
							<th>Chapter</th>
							<th>Level</th>
							<th>Name</th>
							<th>Content</th>
							<th>Edit/Remove</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count=1;

							foreach ($query->result() as $row)
							{
								echo "<tr>";
								echo "<td>".$count.'</td>';
								echo '<td>'.$row->lab_chapter. '</td>';
								echo '<td>'.$row->lab_level. '</td>';
								echo '<td>' . $row->lab_name.'</td>';
								echo '<td>';
									//echo '<textarea id="summernote" type="textarea">';
									echo $row->lab_content;
									//echo ."</textarea>
								echo "</td>";
								echo '<td>'; 
						?>
						<form method="post" id="edit_exercise" name="edit_exercise" 
								action="<?php echo site_url(exercise_edit);" >
							<button type="submit" class="btn btn-primary">Edit Button</button>
							<input="text" name="lab_to_edit" hidden value="<?php echo  $row->lab_id; ?>" >
							
						</form>
						<?php	
								echo "</td>"."<tr>";
								$count++;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	

	<script>
		$(document).ready(function() {
			$('#summernote').summernote({
				height: 500,                 // set editor height
				width:240,					// set editor height
				minHeight: null,             // set minimum height of editor
				maxHeight: null,             // set maximum height of editor
				focus: true,                  // set focus to editable area after initializing summernote
				airmode: true
			});
		});
	</script>
</div>
<!-- nav_body -->