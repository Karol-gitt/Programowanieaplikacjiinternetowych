<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tests extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $this->data['message'] = $this->session->flashdata('message');
        $this->data['error_message'] = $this->session->flashdata('error_message');

        $this->data['tests'] = $this->Tests_model->tests()->result();

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('tests', $this->data);
        $this->load->view('partials/footer');
    }

    function create_test() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        //validate form input
        $this->form_validation->set_rules('test_title', 'NAZWA TESTU', 'required');
        $this->form_validation->set_rules('test_description', 'OPIS TESTU');
        $this->form_validation->set_rules('test_access_code', 'KOD DOSTĘPU', 'required|is_unique[tests.access_code]');
        $this->form_validation->set_rules('test_visible_result', 'WIDOCZNOŚĆ WYNIKU');

        if ($this->form_validation->run() == TRUE) {

            $date = new DateTime();

            $visible = FALSE;

            if (!is_null($this->input->post('test_visible_result'))) {
                $visible = TRUE;
            } else {
                $visible = FALSE;
            }

            $test = array(
                'users_id' => $this->ion_auth->user()->row()->id,
                'title' => $this->input->post('test_title'),
                'description' => $this->input->post('test_description'),
                'access_code' => $this->input->post('test_access_code'),
                'time' => $this->input->post('time'),
                'create' => $date->format('Y-m-d H:i:s'),
                'visible_result' => $visible,
            );

            if ($this->Tests_model->create_test($test)) {
                $this->session->set_flashdata('message', 'Test został utworzony');
                redirect("tests", 'refresh');
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się stworzyć testu');
                redirect("tests", 'refresh');
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            if (validation_errors()) {
                $this->data['error_message'] = validation_errors();
            } else {
                if ($this->Tests_model->errors()) {
                    $this->data['error_message'] = $this->Tests_model->errors();
                } else {
                    $this->data['message'] = $this->session->flashdata('message');
                }
            }
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['test_title'] = array(
                'name' => 'test_title',
                'id' => 'test_title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_title'),
                'class' => 'form-control',
            );

            $this->data['test_description'] = array(
                'name' => 'test_description',
                'id' => 'test_description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_description'),
                'class' => 'form-control',
            );

            $this->data['test_access_code'] = array(
                'name' => 'test_access_code',
                'id' => 'test_access_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_access_code'),
                'class' => 'form-control',
            );
            
            $this->data['time'] = array(
                'name' => 'time',
                'id' => 'time',
                'type' => 'text',
                'value' => 0,
                'class' => 'form-control'
            );

            $checked = FALSE;

            if (!is_null($this->input->post('test_visible_result'))) {
                $checked = TRUE;
            }

            $this->data['test_visible_result'] = array(
                'name' => 'test_visible_result',
                'id' => 'test_visible_result',
                'type' => 'checkbox',
                'checked' => $checked,
                'data-size' => 'small',
                'data-toggle' => 'toggle',
                'data-on' => 'Tak',
                'data-off' => 'Nie',
                'data-onstyle' => 'success',
                'data-offstyle' => 'danger'
            );

            $this->load->view('partials/header');
            if ($this->ion_auth->is_admin()) {
                $this->load->view('partials/admin_menu');
            } else {
                $this->load->view('partials/user_menu');
            }
            $this->load->view('create_test', $this->data);
            $this->load->view('partials/footer');
        }
    }

    public function access_code_check($str, $id) {

        $number = $this->Tests_model->check_access_code($id, $str);

        if ($number > 0) {
            $this->form_validation->set_message('access_code_check', 'Pole {field} musi posiadać unikalną wartość.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function edit_test($id = NULL) {
        if (!$id || empty($id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $test = $this->Tests_model->test($id)->row();

        //validate form input
        $this->form_validation->set_rules('test_title', 'NAZWA TESTU', 'required');
        $this->form_validation->set_rules('test_description', 'OPIS TESTU');
        $this->form_validation->set_rules('test_access_code', 'KOD DOSTĘPU', 'required|callback_access_code_check[' . $id . ']');
        $this->form_validation->set_rules('test_visible_result', 'WIDOCZNOŚĆ WYNIKU');

        if ($this->form_validation->run() == TRUE) {

            $visible = FALSE;

            if (!is_null($this->input->post('test_visible_result'))) {
                $visible = TRUE;
            } else {
                $visible = FALSE;
            }

            $test_updated = array(
                'title' => $this->input->post('test_title'),
                'description' => $this->input->post('test_description'),
                'access_code' => $this->input->post('test_access_code'),
                'time' => $this->input->post('time'),
                'visible_result' => $visible,
            );

            if ($this->Tests_model->update_test($id, $test_updated)) {
                $this->session->set_flashdata('message', 'Test został zmieniony');
                redirect("tests", 'refresh');
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się zapisać zmian');
                redirect("tests/edit_test/" . $id, 'refresh');
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            if (validation_errors()) {
                $this->data['error_message'] = validation_errors();
            } else {
                if ($this->Tests_model->errors()) {
                    $this->data['error_message'] = $this->Tests_model->errors();
                } else {
                    $this->data['message'] = $this->session->flashdata('message');
                }
            }
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['test_title'] = array(
                'name' => 'test_title',
                'id' => 'test_title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_title', $test->title),
                'class' => 'form-control',
            );

            $this->data['test_description'] = array(
                'name' => 'test_description',
                'id' => 'test_description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_description', $test->description),
                'class' => 'form-control',
            );

            $this->data['test_access_code'] = array(
                'name' => 'test_access_code',
                'id' => 'test_access_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('test_access_code', $test->access_code),
                'class' => 'form-control',
            );
            
            $this->data['time'] = array(
                'name' => 'time',
                'id' => 'time',
                'type' => 'text',
                'value' => $test->time,
                'class' => 'form-control'
            );

            $checked = FALSE;


            if ($test->visible_result == 1) {
                $checked = TRUE;
            }



            $this->data['test_visible_result'] = array(
                'name' => 'test_visible_result',
                'id' => 'test_visible_result',
                'type' => 'checkbox',
                'checked' => $checked,
                'data-size' => 'small',
                'data-toggle' => 'toggle',
                'data-on' => 'Tak',
                'data-off' => 'Nie',
                'data-onstyle' => 'success',
                'data-offstyle' => 'danger'
            );

            $this->load->view('partials/header');
            if ($this->ion_auth->is_admin()) {
                $this->load->view('partials/admin_menu');
            } else {
                $this->load->view('partials/user_menu');
            }
            $this->load->view('edit_test', $this->data);
            $this->load->view('partials/footer');
        }
    }

    function delete_test($id = NULL) {
        if (!$id || empty($id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_test($id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }
    
    function delete_test_attempt($id = NULL) {
        if (!$id || empty($id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_test_attempt($id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }

    function test($id = NULL) {
        unset($_SESSION['question_answers']);

        if (!$id || empty($id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $test = $this->Tests_model->test($id)->row();


        if ($this->input->post('question')) {
            $this->Tests_model->add_questions_to_test($id, $this->input->post('question'));
        }


        $this->data['test'] = $test;
        $this->data['categories'] = $this->Tests_model->categories()->result();
        $this->data['questions'] = $this->Tests_model->test_questions($id)->result();

        $this->data['message'] = '';
        $this->data['error_message'] = '';

        $checked = FALSE;

        if ($test->random_questions == 1) {
            $checked = TRUE;
        }

        $this->data['random_questions'] = array(
            'name' => 'random_questions',
            'id' => 'random_questions',
            'type' => 'checkbox',
            'checked' => $checked,
            'data-size' => 'small',
            'data-toggle' => 'toggle',
            'data-on' => 'Tak',
            'data-off' => 'Nie',
            'data-onstyle' => 'success',
            'data-offstyle' => 'danger',
            'data-testid' => $id
        );

        $this->data['rate_summary'] = $this->Tests_model->rate_summary($id)->summary;

        //$questions_to_add = $this->input->post('question');



        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('test', $this->data);
        $this->load->view('partials/footer');
    }

    function random_questions($id) {
        if (!$id || empty($id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $this->Tests_model->random_questions_toggle($id);
    }

    function get_questions($test_id, $category = NULL) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $questions = array();

        if ($category != NULL && $category != 0) {
            $questions = $this->Tests_model->questions($category)->result();
        } else {
            $questions = $this->Tests_model->questions()->result();
        }

        $test_questions = $this->Tests_model->test_questions($test_id)->result();

        $data = '';

        foreach ($questions as $question) {

            $in_array = false;

            foreach ($test_questions as $test_question) {
                if ($question->id == $test_question->id) {
                    $in_array = true;
                    break;
                }
            }

            if (!$in_array) {
                $data .= '<div class="list-group-item">
                        <div class="checkbox" style="margin-top: 0px; margin-bottom: 0px;">
                                <label class="checkbox-custom" data-initialize="checkbox" style="width: 100%;">                                
                                    <input type="checkbox" name="question[]" value="' . $question->id . '">
                                    ' . $question->question_name . '<span class="pull-right">' . $question->type . '</span>
                                </label>                            
                            </div>
                        </div>';
            }
        }

        echo $data;
    }

    function get_test_questions($test_id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $test_questions = $this->Tests_model->test_questions($test_id)->result();

        $data = '';

        foreach ($test_questions as $question) {


            $data .= '<li class="list-group-item" id="recordsArray_' . $question->id . '">
                            ' . $question->question_name . '<span class="pull-right">' . $question->type . '</span>
                        </li>';
        }

        echo $data;
    }

    function delete_question($id = NULL, $test_id = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/questions', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_question_from_test($id, $test_id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }

    function delete_questions($id = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/questions', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_questions_from_test($id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }

    function save_order() {
        $test_id = $_POST['testid'];

        $updateRecordsArray = $_POST['recordsArray'];

        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {

            //$query = "UPDATE zdjecia SET kolejnosc = " . $listingCounter . " WHERE id = " . $recordIDValue;
            //mysql_query($query) or die('Error, insert query failed');

            $this->Tests_model->update_order($test_id, $recordIDValue, $listingCounter);

            $listingCounter = $listingCounter + 1;
        }

        //redirect('tests/test/'.$test_id, 'refresh');
    }

    function test_attempts($test_id = NULL) {
        if (!$test_id || empty($test_id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $this->data['test_id'] = $test_id;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['error_message'] = $this->session->flashdata('error_message');

        $this->data['test_attempts'] = $this->Tests_model->test_attempts($test_id)->result();

        $this->data['summary'] = array();

        foreach ($this->data['test_attempts'] as $attempt) {
            $points = $attempt->sumary;
            $total = $this->Tests_model->rate_summary($attempt->tests_id)->summary;
            $percentage = round(($points / $total) * 100,2);
            $this->data['summary'][$attempt->id] = $points . '/' . $total . ' (' . $percentage . '%)';
        }

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('test_attempts', $this->data);
        $this->load->view('partials/footer');
    }

    function test_attempt($attempt_id = NULL) {
        if (!$attempt_id || empty($attempt_id)) {
            redirect('tests', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $attempt = $this->Tests_model->test_attempt($attempt_id)->row();
        
        $this->data['student'] = $attempt->student;

        $test = $this->Tests_model->test($attempt->tests_id)->row();

        $this->data['visible_result'] = 1;
        $this->data['attempt_summary'] = $attempt->sumary;
        $this->data['max_attempt_summary'] = $this->Tests_model->rate_summary($test->id)->summary;
        $this->data['attempt_summary_percentage'] = round(($attempt->sumary / $this->data['max_attempt_summary']) * 100,2);

        $questions = array();

        $questions = $this->Tests_model->test_questions_attempt($test->id)->result();


        //ładujemy biblioteke paginacji
        $this->load->library('pagination');

        // Określamy początkowy adres url dla paginacji
        $config['base_url'] = site_url('tests/test_attempt/' . $attempt_id);
        // Zliczamy ilość wszystkich wpisów
        $config['total_rows'] = sizeof($questions);
        // Określamy liczbę wpisów na stronie
        $config['per_page'] = 1;
        $config['uri_segment'] = 4;
        $config['num_links'] = 1;

        // Zmienne odpowiedzialne za ustalenie wyglądu paginacji (dla Twitter Bootstrap)
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $config['prev_link'] = 'Poprzednie pytanie';
        $config['next_link'] = 'Następne pytanie';

        // Inicjalizujemy bibliotekę paginacji z powyższymi ustawieniami
        $this->pagination->initialize($config);

        // Przypisujemy do zmiennej numery dostępnych stron
        $this->data['pagination'] = $this->pagination->create_links();

        // Wczytujemy wpisy, jako parametry podając kryteria
        $question = array_slice($questions, $this->uri->rsegment(4), $config['per_page']);

        $answers = array();

        if ($question[0]->type == 'test-one' || $question[0]->type == 'test-multi') {
            $answers_from_database = $this->Tests_model->answers($question[0]->id)->result();
            $answers = array();
            foreach ($answers_from_database as $answer) {
                $answers[] = $answer->answer;
            }
        } else {
            $answers = explode(';', $question[0]->rightanswer);
        }

        $this->data['question_rate'] = $question[0]->rate;

        $your_answer = $this->Tests_model->get_answer($attempt_id, $question[0]->id);


        if ($question[0]->type == 'short') {
            $possible_answers = explode(';', $question[0]->rightanswer);
            $this->data['right_answers'] = $possible_answers;
            if (isset($your_answer->answer) && in_array($your_answer->answer, $possible_answers)) {
                $this->data['success'] = 'has-success';
            } else {
                $this->data['success'] = 'has-error';
            }
        } else if ($question[0]->type == 'test-one') {
            $this->data['right_answers'] = $question[0]->rightanswer;
//                    if ($question[0]->rightanswer == $your_answer->answer) {
//                        $question_answer['rate'] = $question[0]->rate;
//                    } else {
//                        $question_answer['rate'] = 0;
//                    }
        } else if ($question[0]->type == 'test-multi') {
            $possible_answers = explode(';', $question[0]->rightanswer);
            $this->data['right_answers'] = $possible_answers;
            if (isset($your_answer->answer)) {
                $user_answer = explode(';', $your_answer->answer);
            } else {
                $user_answer = array();
            }
        }

        //$your_answer = $this->Tests_model->get_answer($attempt_id, $question[0]->id);

        if ($your_answer) {
            if ($question[0]->type == 'test-one' || $question[0]->type == 'short') {
                $this->data['your_answer'] = $your_answer->answer;
            } else {
                $this->data['your_answer'] = explode(';', $your_answer->answer);
            }
        }

        $this->data['question'] = $question[0];
        $this->data['answers'] = $answers;

        $this->data['test'] = $test;

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('attempt', $this->data);
        $this->load->view('partials/footer');
    }

}
