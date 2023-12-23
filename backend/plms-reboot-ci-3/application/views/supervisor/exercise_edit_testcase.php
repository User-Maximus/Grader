
<!--- Testcase -->
<?php
	for($testcase_count = 0; $testcase_count < $num_of_testcase; $testcase_count++) {
		$testcase_row = $testcase_array[$testcase_count];
		$form_id = 'testcase_'.$testcase_count;
		$action = 'exercise_testcase_update';

		$testcase_html ='	
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-left" style="font-size:24px;color:blue;">Testcase : #';
		$testcase_html .= $testcase_count+1;
		$testcase_html .= '
				</div>';
		$testcase_html .= '<span><input type="text" name="testcase_note" form="'.$form_id.'" value="'.$testcase_row['testcase_note']. '" ></span>';


		$testcase_html .= '
				<label>Show to student : </label>
				<label class="radio-inline">
					<input type="radio" name="show_to_student" id="show_to_student_yes" value="yes" ';
					$testcase_html .= 'form="'.$form_id.'"';
					if ($testcase_row['show_to_student']=="yes")
						$testcase_html .= " checked";
					$testcase_html .= '>Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="show_to_student" id="show_to_student_no" value="no" ';
					$testcase_html .= 'form="'.$form_id.'"';
					if ($testcase_row['show_to_student']=="no")
						$testcase_html .= " checked";
					$testcase_html .= '>No
				</label>
				
				<label>Use for marking : </label>
				<label class="radio-inline">
					<input type="radio" name="active" id="use_for_marking_yes" value="yes" ';
					$testcase_html .= 'form="'.$form_id.'"';
					if ($testcase_row['active']=="yes")
						$testcase_html .= " checked";
					$testcase_html .= ' >Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="active" id="use_for_marking_no" value="no" ';
					$testcase_html .= 'form="'.$form_id.'"';
					if ($testcase_row['active']=="no")
						$testcase_html .= " checked";
					$testcase_html .= '	>No
				</label>
				
				<input class="pull-right" type="submit" value="Testcase Submit" ';
				$testcase_html .= 'form="'.$form_id.'" >';
				$testcase_html .= '
			</div>';
		$lines_of_sourcecode = substr_count($sourcecode_content, "\n" );
						
		$testcase_html .= '	
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div  style="font-family: Courier;font-size: 16px;border:2px blue;">';
		$testcase_html .= '<code><textarea cols="80" rows="5" style="background:black;color:white;" name="testcase_content" form="'.$form_id.'" >';
		$testcase_html .= $testcase_row['testcase_content'];
		$testcase_html .= '</textarea></code>'; 
		$testcase_html .= '
						</div>
					</div>
		
					<div class="col-md-6">
						<div  style="font-family: Courier;font-size: 16px;border:2px blue;">';
		$testcase_html .= '<code><textarea cols="80" rows="'.$lines_of_sourcecode.'" style="background:black;color:white;" readonly>';
		$testcase_html .= $testcase_row['testcase_output'];
		$testcase_html .='</textarea></code>'; 
		$testcase_html .= '
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
echo $testcase_html;
$form_html = '<form method="post" id="'.$form_id.'"';
$form_html .= ' action="'.$action.'"';
$form_html .= '> ';
$form_html .= '<input type="text" name="exercise_id" hidden value="' . $testcase_row['exercise_id'] . '" >';
$form_html .= '<input type="text" name="testcase_id" hidden value="' . $testcase_row['testcase_id'] . '" >';
$form_html .= '</form>';
echo $form_html;
}
?>

<!--- Testcase End -->

<div class="row">
	<div class="col-md-4">
		<input type="submit"  form="exercise_testcase_add" value="Add Testcase" >
	</div>
	<form method="post" id="exercise_testcase_add" action="exercise_testcase_add">
		<input type="text" name='exercise_id' value="<?php echo $exercise_id; ?>" hidden>
	</form
</div>
			



