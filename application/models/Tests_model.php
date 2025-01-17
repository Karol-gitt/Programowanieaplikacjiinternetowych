<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tests_model extends CI_Model {

    protected $errors;
    protected $messages;

    public function __construct() {
        parent::__construct();
        $this->errors = array();
        $this->messages = array();
    }

    //pobierz kategorie pytań
    public function categories() {
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where(array('users_id' => $user_id))->get("questions_categories");
    }
    
    public function tests() {
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where(array('users_id' => $user_id))->get("tests");
    }

    //utwórz nową kategorię
    public function create_category($name = FALSE) {

        if (!$name) {
            $this->set_error('Należy podać nazwę kategorii');
            return FALSE;
        }

//        $existing_category = $this->db->get_where("questions_categories", array('name' => $name))->num_rows();
//        if ($existing_group !== 0) {
//            $this->set_error('Taka kategoria już istnieje');
//            return FALSE;
//        }

        $data = array('name' => $name, 'users_id' => $this->ion_auth->user()->row()->id);

        // insert the new category
        $this->db->insert("questions_categories", $data);
        $category_id = $this->db->insert_id();

        // report success
        $this->set_message('Kategoria została utworzona');
        // return the brand new group id
        return $category_id;
    }

    //pobierz kategorię
    public function category($id = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer kategorii');
            return FALSE;
        }

        return $this->db->where(array('id' => $id))->get("questions_categories");
    }
    
    public function test($id = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer kategorii');
            return FALSE;
        }

        return $this->db->where(array('id' => $id))->get("tests");
    }
    
    public function test_attempt($id = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer kategorii');
            return FALSE;
        }

        return $this->db->where(array('id' => $id))->get("test_attempts");
    }
    
    public function test_attempts($test_id = FALSE) {
        if (!$test_id) {
            $this->set_error('Należy podać numer kategorii');
            return FALSE;
        }

        return $this->db->where(array('tests_id' => $test_id))->get("test_attempts");
    }
    
    public function check_access_code($id,$code) {
        return $this->db->query("SELECT * FROM tests WHERE access_code = '".$code."' and id != ".$id)->num_rows();
    }

    public function question($id = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer pytania');
            return FALSE;
        }

        return $this->db->where(array('id' => $id))->get("questions");
    }

    public function answers($id = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer pytania');
            return FALSE;
        }

        return $this->db->where(array('questions_id' => $id))->get("questions_answers");
    }
    
    public function get_answer($attempt_id,$question_id) {
        return $this->db->where(array('test_attempts_id' => $attempt_id,'questions_id' => $question_id))->get("student_answers")->row();
    }
    
    public function save_answer($question_answer){
        return $this->db->insert('student_answers',$question_answer);
    }
    
    public function update_answer($question_answer){
        return $this->db->update('student_answers',$question_answer,array('questions_id' => $question_answer['questions_id'],'test_attempts_id' => $question_answer['test_attempts_id']));
    }

    public function update_category($id = FALSE, $category_name = FALSE) {
        if (!$id) {
            $this->set_error('Należy podać numer kategorii');
            return FALSE;
        }

        $data = array();

        if (!empty($category_name)) {
            $data['name'] = $category_name;
        }

        if (!$this->db->update("questions_categories", $data, array('id' => $id))) {
            $this->set_error('Nie udało się zapisać zmian');
        }

        $this->set_message('Kategoria została zmieniona');

        return TRUE;
    }

    public function delete_category($id = FALSE) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }
        $category = $this->category($id)->row();

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;

        $this->db->delete("questions_categories", array('id' => $id));
        
        $this->db->db_debug = $db_debug;

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć kategorii');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Kategoria została usunięta');
        return TRUE;
    }

    public function questions($category = NULL) {
        $user_id = $this->ion_auth->user()->row()->id;
        if ($category == NULL) {
            $sql = "SELECT q.id, q.name as question_name, q.type, c.name as category_name, q.question FROM questions q INNER JOIN questions_categories c ON q.category_id = c.id WHERE c.users_id = ?";
            $data = $this->db->query($sql, array($user_id));
//            $data = $this->db->
//                    join('questions', 'questions.category_id = questions_categories.id', 'inner')->
//                    where(array('questions_categories.users_id' => $user_id))->
//                    get('questions_categories');
        } else {
            $sql = "SELECT q.id, q.name as question_name, q.type, c.name as category_name, q.question FROM questions q INNER JOIN questions_categories c ON q.category_id = c.id WHERE c.users_id = ? and c.id = ?";
            $data = $this->db->query($sql, array($user_id, $category));
//            $data = $this->db->
//                    join('questions', 'questions.category_id = questions_categories.id', 'inner')->
//                    where(array('questions_categories.users_id' => $user_id, 'questions_categories.id' => $category))->
//                    get('questions_categories');
        }

        return $data;
    }

    public function delete_question($id = FALSE) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;
        
        // remove the group itself
        $this->db->delete("questions", array('id' => $id));

        $this->db->db_debug = $db_debug;
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć pytania');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Pytanie zostało usunięte');
        return TRUE;
    }
    
    public function delete_questions_from_test($id = FALSE) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;
        
        $this->db->delete("questions_has_tests", array('tests_id' => $id));

        $this->db->db_debug = $db_debug;
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć pytań');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Pytania zostały usunięte');
        return TRUE;
    }
    
    public function delete_question_from_test($id = FALSE, $test_id = NULL) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;
        
        $this->db->delete("questions_has_tests", array('questions_id' => $id, 'tests_id' => $test_id));

        $this->db->db_debug = $db_debug;
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć pytania');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Pytanie zostało usunięte');
        return TRUE;
    }
    
    public function delete_test($id = FALSE) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;
        
        // remove the group itself
        $this->db->delete("tests", array('id' => $id));

        $this->db->db_debug = $db_debug;
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć testu');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Test został usuniety');
        return TRUE;
    }
    
    public function delete_test_attempt($id = FALSE) {
        // bail if mandatory param not set
        if (!$id || empty($id)) {
            return FALSE;
        }

        $this->db->trans_begin();

        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;
        
        if(!$this->test_attempt($id)->row()){
            $this->set_error('Nie można usunąć podejścia');
        }else{
            $this->db->delete("test_attempts", array('id' => $id));
        }   

        $this->db->db_debug = $db_debug;
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('Nie można usunąć podejścia');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('Podejście zostało usunięte');
        return TRUE;
    }

    public function create_question($question = NULL, $answers = NULL) {
        if (!$question) {
            $this->set_error('Należy podać dane pytania');
            return FALSE;
        }

        if ($question['type'] == 'short') {

            $this->db->insert("questions", $question);
        } else {

            $this->db->insert("questions", $question);
            $inserted_id = $this->db->insert_id();

            foreach ($answers as $answer) {
                if (trim($answer) != '') {
                    $data = array(
                        'questions_id' => $inserted_id,
                        'answer' => trim($answer)
                    );

                    $this->db->insert("questions_answers", $data);
                }
            }
        }

        $this->set_message('Pytanie zostało utworzone');

        return TRUE;
    }
    
    public function create_test($test = NULL) {
        if (!$test) {
            $this->set_error('Należy podać dane testu');
            return FALSE;
        }

        if($this->db->insert("tests", $test)){
            $this->set_message('Test został utworzony');
            return TRUE;
        }else{
            $this->set_error('Nie udało się utworzyć testu');
            return FALSE;
        }        
    }

    public function update_question($id, $question = NULL, $answers = NULL) {
        if (!$question) {
            $this->set_error('Należy podać dane pytania');
            return FALSE;
        }

        if ($question['type'] == 'short') {

            $this->db->update("questions", $question, array('id' => $id));
        } else {

            $this->db->update("questions", $question, array('id' => $id));
            $this->db->delete("questions_answers", array('questions_id' => $id));
            foreach ($answers as $answer) {
                if (trim($answer) != '') {
                    $data = array(
                        'questions_id' => $id,
                        'answer' => trim($answer)
                    );

                    $this->db->insert("questions_answers", $data);
                }
            }
        }

        $this->set_message('Pytanie zostało zmienione');

        return TRUE;
    }
    
    public function update_test($id, $test = NULL) {
        if (!$test) {
            $this->set_error('Należy podać dane pytania');
            return FALSE;
        }

        if (!$this->db->update("tests", $test, array('id' => $id))) {
            $this->set_error('Nie udało się zapisać zmian');
        }       

        $this->set_message('Pytanie zostało zmienione');

        return TRUE;
    }
    
    function add_questions_to_test($id = NULL, $questions){
        
        $order = $this->db->query("SELECT * FROM questions_has_tests WHERE tests_id = ".$id)->num_rows();
        
        if($order != 0){
            $order = $this->db->query("SELECT max(qht.order) as max_order FROM questions_has_tests qht WHERE tests_id = ".$id)->row()->max_order;
        }
        
        //save setting
        $db_debug = $this->db->db_debug;
        //disable debugging for queries
        $this->db->db_debug = FALSE;       
        
        foreach($questions as $question){
            $order++;
            //if($this->db->query)
            $this->db->insert("questions_has_tests",array('questions_id' => $question, 'tests_id' => $id, 'order' => $order));
        }
        
        $this->db->db_debug = $db_debug;
        
    }
    
    function test_questions($id){
        return $this->db->query("SELECT q.id, q.name as question_name, q.type, c.name as category_name, q.rate FROM questions q INNER JOIN questions_categories c ON q.category_id = c.id INNER JOIN questions_has_tests qht ON qht.questions_id = q.id WHERE qht.tests_id = ".$id." ORDER BY qht.order ASC");
    }
    
    function test_questions_pagination($id,$limit,$offset = NULL){
        if(empty($offset)){
            $offset = 0;
        }
        return $this->db->query("SELECT q.id, q.name, q.type, q.rate, q.rightanswer, q.random_answers, q.question FROM questions q INNER JOIN questions_has_tests qht ON qht.questions_id = q.id WHERE qht.tests_id = ".$id." ORDER BY qht.order ASC LIMIT ".$offset.", ".$limit);
    }
    
    function test_questions_attempt($id){        
        return $this->db->query("SELECT q.id, q.name, q.type, q.rate, q.rightanswer, q.random_answers, q.question FROM questions q INNER JOIN questions_has_tests qht ON qht.questions_id = q.id WHERE qht.tests_id = ".$id." ORDER BY qht.order ASC");
    }
    
    function update_order($test_id,$question_id,$order){
        $this->db->query("UPDATE questions_has_tests q SET q.order = " . $order . " WHERE q.tests_id = " . $test_id ." and q.questions_id = " .$question_id);
    }

    function questions_types() {
        return $this->db->get("questions_types");
    }
    
    function rate_summary($id){
        return $this->db->query("SELECT sum(q.rate) as summary FROM questions q INNER JOIN questions_has_tests qht ON qht.questions_id = q.id WHERE qht.tests_id = ".$id)->row();
    }
    
    function random_questions_toggle($id){
        if($this->db->query("SELECT random_questions FROM tests WHERE id = ".$id)->row()->random_questions == 0){
            $this->db->update("tests",array('random_questions' => 1),array('id' => $id));
        }else{
            $this->db->update("tests",array('random_questions' => 0),array('id' => $id));
        }
    }
    
    function get_test($code){
        return $this->db->where(array('access_code' => $code))->get('tests');
    }
    
    function start_test($test_id, $student){
        $date = new DateTime();
        
        $data = array(
            'tests_id' => $test_id,
            'student' => $student,
            'start_time' => $date->format('Y-m-d H:i:s')
        );
        
        $this->db->insert('test_attempts',$data);
        
        $attempt = array(
            'id' => $this->db->insert_id(),
            'tests_id' => $test_id,
            'student' => $student,
            'start_time' => $date->format('Y-m-d H:i:s')
        );
        
        return $attempt;
        
    }
    
    function end_test($attempt){
        return $this->db->update('test_attempts',$attempt,array('id' => $attempt->id));
    }
    
    function attempt_summary($attempt_id){
        return $this->db->query("SELECT sum(rate) as summary FROM student_answers WHERE test_attempts_id = ".$attempt_id)->row();
    }

    public function set_error($error) {
        $this->errors[] = $error;

        return $error;
    }

    public function errors() {
        $_output = '';
        foreach ($this->errors as $error) {
            $_output .= "<p>" . $error . "</p>";
        }

        return $_output;
    }

    public function set_message($message) {
        $this->messages[] = $message;

        return $message;
    }

    public function messages() {
        $_output = '';
        foreach ($this->messages as $message) {
            $_output .= "<p>" . $message . "</p>";
        }

        return $_output;
    }

}
