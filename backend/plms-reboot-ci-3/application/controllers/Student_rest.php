<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once(APPPATH . "/controllers/MY_RestController.php");

/**
 * Class Student_rest
 *
 * This class extends the MY_RestController class and provides functionality to retrieve data related to a student's lab work.
 */
class Student_rest extends MY_RestController
{
  /**
   * Student_rest constructor.
   *
   * Initializes the class and loads the student_model_rest model.
   */
  public function __construct()
  {
    parent::__construct();
    $this->protected();

    $this->load->model('auth_model_rest');
    $this->auth_model_rest->update_last_seen($this->session->userdata('id'));

    $this->load->model('student_model_rest');
  }

  /**
   * Retrieves data related to a student's lab work and returns it in a specific format.
   */

  private function handleError(Exception $e)
  {
    return $this->response([
      'status' => FALSE,
      'message' => 'Error: ' . $e->getMessage(),
      'payload' => null,
    ], $e->getCode()); // Use the provided HTTP status code
  }

  public function getChapterList_get()
  {
    try {
      $this->load->model("lab_model_rest");

      $stu_id = $this->query('stu_id');
      $stu_group = $this->student_model_rest->get_student_record($stu_id)['stu_group'];

      $permission = $this->lab_model_rest->get_group_permission($stu_group);
      $lab_data = $this->lab_model_rest->setup_student_lab_data($stu_id, $stu_group);
      $student_marking_all_items = $this->lab_model_rest->get_a_student_marking_for_all_submitted_items($stu_id);

      foreach ($student_marking_all_items as $row) {
        $marking = $row['max_marking'] ?? 0;
        $chapter_id = $row['chapter_id'];
        $item_id = $row['item_id'];
        $lab_data[$chapter_id][$item_id]['stu_lab']['marking'] = $marking;
      }

      $data = [];

      for ($i = 1; $i < count($permission) + 1; $i++) {
        $temp = $permission[$i];
        $temp['chapter_id'] = $i;
        $data[$i] = $temp;
        $data[$i]['items'] = array_values($lab_data[$i]);
      }

      return $this->response(array_values($data), RestController::HTTP_OK);
    } catch (Exception $e) {
      return $this->handleError($e);
    }
  }

  public function getStudentAssignedExercise_get()
  {
    try {
      $this->db->trans_start(); // Start the transaction

      // Retrieve the student ID, chapter ID, and item ID from the query parameters
      $stu_id = $this->query('stu_id');
      $chapter_id = $this->query('chapter_id');
      $item_id = $this->query('item_id');

      // Check if the provided data is valid
      if (!isset($stu_id, $chapter_id, $item_id)) {
        throw new Exception('Invalid data provided.', RestController::HTTP_BAD_REQUEST);
      }

      // Retrieve the exercise ID assigned to the student based on the provided IDs
      $exercise_id = $this->student_model_rest->get_student_assigned_exercise_id($stu_id, $chapter_id, $item_id);

      // Retrieve the group ID of the student
      $group_id = $this->student_model_rest->get_student_record($stu_id)['stu_group'];

      // Check if exercise_id is null
      if (empty($exercise_id)) {
        // Retrieve the exercise ID pool for the given group, chapter, and item
        $exercise_random_pool = unserialize($this->student_model_rest->get_exercise_random_pool($group_id, $chapter_id, $item_id)['exercise_id_list']);

        // Check if the exercise pool is empty
        if (empty($exercise_random_pool)) {
          throw new Exception('No exercise available.', RestController::HTTP_BAD_REQUEST);
        }

        // Select a random exercise ID from the pool
        $exercise_id = $exercise_random_pool[array_rand($exercise_random_pool)];

        // Upsert the student's assigned chapter item with the selected exercise ID
        $this->student_model_rest->upsert_student_assigned_chapter_item($stu_id, $chapter_id, $item_id, $exercise_id);
      }

      $this->load->model('lab_model_rest');

      // Retrieve the details of the assigned exercise using the exercise ID
      $lab_exercise = $this->lab_model_rest->get_lab_exercise_by_id($exercise_id);

      // Retrieve the name of the chapter using the chapter ID
      $lab_exercise['chapter_name'] = $this->lab_model_rest->get_chapter_name($chapter_id);

      $lab_exercise['user_defined_constraints'] = json_decode($lab_exercise['user_defined_constraints']);
      $lab_exercise['suggested_constraints'] = json_decode($lab_exercise['suggested_constraints']);

      // Remove sensitive information from the exercise details
      unset($lab_exercise['sourcecode']);
      unset($lab_exercise['sourcecode_content']);

      $this->db->trans_complete(); // Complete the transaction

      // Check if the transaction failed
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Transaction failed.', RestController::HTTP_INTERNAL_ERROR);
      } else {
        // Return the exercise details
        return $this->response($lab_exercise, RestController::HTTP_OK);
      }
    } catch (Exception $e) {
      return $this->handleError($e);
    }
  }

  public function getStudentCardInfo_get()
  {
    try {
      $stu_id = $this->query('stu_id');

      if (!isset($stu_id)) {
        throw new Exception('Invalid data provided.', RestController::HTTP_BAD_REQUEST);
      }

      $student = $this->student_model_rest->get_student_record($stu_id);

      $this->load->model('lab_model_rest');

      $group_data = $this->lab_model_rest->get_class_schedule_by_group_id($student['stu_group']);

      $this->load->model('supervisor_model_rest');

      $lecturer = $this->supervisor_model_rest->get_supervisor_fullname_by_id($group_data['lecturer']);

      $ip_address = $this->input->ip_address();

      $data = [
        'stu_id' => $student['stu_id'],
        'stu_firstname' => $student['stu_firstname'],
        'stu_lastname' => $student['stu_lastname'],
        'mid_score' => $student['mid_score'],
        'group_id' => $student['stu_group'],
        'group_no' => $group_data['group_no'],
        'day_of_week' => $group_data['day_of_week'],
        'time_start' => $group_data['time_start'],
        'time_end' => $group_data['time_end'],
        'year' => $group_data['year'],
        'semester' => $group_data['semester'],
        'lecturer' => $lecturer,
        'stu_ip' => $ip_address,
      ];

      return $this->response($data, RestController::HTTP_OK);
    } catch (Exception $e) {
      return $this->handleError($e);
    }
  }

  public function studentExerciseSubmit_post()
  {
    $stu_id = $this->post('stu_id');
    $chapter_id = $this->post('chapter_id');
    $item_id = $this->post('item_id');
    $sourcecode = $this->post('sourcecode');


    if (empty($chapter_id) || empty($item_id) || empty($stu_id) || empty($sourcecode)) {
      return $this->response(['message' => 'Invalid request body'], RestController::HTTP_BAD_REQUEST);
    }

    $exercise_id = $this->student_model_rest->get_student_assigned_exercise_id($stu_id, $chapter_id, $item_id);

    // get all submitted exercise
    $this->load->model('lab_model_rest');
    $submission_list = $this->lab_model_rest->get_student_submission($stu_id, $exercise_id);

    // get the attemps number as a string
    $attemps = count($submission_list) + 1;

    // if the digit of attemps is less than 4 then add 0 to the front
    if (strlen($attemps) < 4) {
      $attemps = str_pad($attemps, 4, "0", STR_PAD_LEFT);
    }

    $directory_path = STUDENT_CFILES_FOLDER;
    $file_name = $stu_id . "_" . $chapter_id . "_" . $item_id . "_" . $attemps . ".py";

    // write the sourcecode to the file at the directory
    $writer = fopen($directory_path . $file_name, "w");
    fwrite($writer, $sourcecode);
    fclose($writer);

    // insert the submission to the database
    $submission = array(
      'stu_id' => $stu_id,
      'exercise_id' => $exercise_id,
      'status' => 'pending',
      'sourcecode_filename' => $file_name,
      'marking' => 0,
      'time_submit' => date("Y-m-d H:i:s"),
      'inf_loop' => 'No',
      'output' => null,
      'result' => null,
      'error_message' => null,
    );

    $this->db->trans_start();
    $inserted_row = $this->lab_model_rest->exercise_submission_add($submission);

    $testcase_list = $this->lab_model_rest->get_testcase_array($exercise_id);
    $exercise =  $this->lab_model_rest->get_exercise_by_id($exercise_id);

    try {
      $connection = new AMQPStreamConnection('rabbitmq', 5672, 'plms', 'plmskmitl2023');
      $channel = $connection->channel();
      $channel->queue_declare('task-queue', false, true, false, false);

      $job_id = uniqid();

      $message = new AMQPMessage(json_encode(array(
        'job_id' => $job_id,
        'job_type' => 'exercise-submit',
        'submission_id' => $inserted_row["submission_id"],
        'sourcecode' => file_get_contents($directory_path . $file_name),
        'testcase_list' => $testcase_list,
        'keyword_constraints' => $exercise['user_defined_constraints'],
      )));

      $channel->basic_publish($message, '', 'task-queue');

      $channel->close();
      $connection->close();

      // If the AMQP message was sent successfully, commit the transaction
      $this->db->trans_commit();
    } catch (Exception $e) {
      // If an error occurred, roll back the transaction
      $this->db->trans_rollback();

      $this->response([
        'status' => 'error',
        'message' => 'An error occurred while running testcases',
        'error' => $e->getMessage(),
      ], RestController::HTTP_INTERNAL_ERROR);
    }

    $this->response([
      'status' => 'success',
      'message' => 'Submission are being run',
      'job_id' => $job_id,
      'submission_id' => $inserted_row,
    ], RestController::HTTP_OK);
  }
}
