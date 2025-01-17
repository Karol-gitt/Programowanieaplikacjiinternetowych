<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    public function start_test($id) {
        if (!$id || empty($id)) {
            redirect('welcome', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('student', 'IMIĘ I NAZWISKO', 'required');

        $test = $this->Tests_model->test($id)->row();
        

        if ($this->form_validation->run() == TRUE) {

            $attempt = $this->Tests_model->start_test($id, $this->input->post('student'));

            if ($attempt) {

                $cookie = array(
                    'name' => 'test_attempt',
                    'value' => json_encode($attempt),
                    'expire' => 7200
                );

                set_cookie($cookie);
                $this->session->set_userdata('attempt_id', $attempt['id']);
                //redirect('test/test_attempt/' . $id . '/' . $attempt['id'], 'refresh');
                redirect('test/test_attempt/' . $id, 'refresh');
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się uruchomić testu');
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

            $this->data['student'] = array(
                'name' => 'student',
                'id' => 'student',
                'type' => 'text',
                'value' => $this->form_validation->set_value('student'),
                'class' => 'form-control',
            );

            $this->data['test'] = $test;

            $this->load->view('partials/header');
            $this->load->view('partials/menu');
            $this->load->view('start_test', $this->data);
            $this->load->view('partials/footer');
        }
    }

    function cancel_attempt() {
        $this->session->unset_userdata('test_questions');
        $this->session->unset_userdata('attempt_id');
        delete_cookie('test_attempt');
        redirect('welcome', 'refresh');
    }

    function test_attempt($test_id = NULL) {
        if (!$test_id || empty($test_id)) {
            redirect('welcome', 'refresh');
        }
        //$this->session->unset_userdata('test_questions');

        if ($this->session->has_userdata('attempt_id')) {
            $attempt_id = $this->session->userdata('attempt_id');
        } else {
            redirect('welcome', 'refresh');
        }

        $this->data['test'] = $this->Tests_model->test($test_id)->row();

        $test_attempt = $this->Tests_model->test_attempt($attempt_id)->row();
        $this->data['test_attempt'] = $test_attempt;
        
        if($this->data['test']->time>0){
            $test_end_time = new DateTime($test_attempt->start_time);
            $test_end_time->add(new DateInterval('PT' . $this->data['test']->time . 'M'));
            $this->data['test_end_time'] = $test_end_time->format('Y-m-d H:i:s');
        }

        if (!empty($test_attempt->end_time)) {
            redirect('welcome', 'refresh');
        }

        $questions = array();

        if ($this->session->has_userdata('test_questions')) {
            $questions = $this->session->userdata('test_questions');
        } else {
            $questions = $this->Tests_model->test_questions_attempt($test_id)->result();

            if ($this->data['test']->random_questions == 1) {
                shuffle($questions);
            }

            $this->session->set_userdata('test_questions', $questions);
        }

        //ładujemy biblioteke paginacji
        $this->load->library('pagination');

        // Określamy początkowy adres url dla paginacji
        $config['base_url'] = site_url('test/test_attempt/' . $test_id);
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

            if ($question[0]->random_answers == 1) {
                shuffle($answers);
            }
        } else {
            $answers = explode(';', $question[0]->rightanswer);
        }
        
        $this->data['question_rate'] = $question[0]->rate;

        $your_answer = $this->Tests_model->get_answer($attempt_id, $question[0]->id);

        if ($this->input->post('answer')) {

            $user_answer = $this->input->post('answer');

            $question_answer = array(
                'questions_id' => $question[0]->id,
                'test_attempts_id' => $attempt_id
            );

            $answer_string = '';

            if (is_array($user_answer)) {
                $i = 0;
                foreach ($user_answer as $ans) {
                    if ($i == 0) {
                        $answer_string .= trim($ans);
                    } else {
                        $answer_string .= ';' . trim($ans);
                    }
                    $i++;
                }
            } else {
                $answer_string = trim($user_answer);
            }

            $question_answer['answer'] = $answer_string;

            if ($question[0]->type == 'short') {
                $possible_answers = explode(';', $question[0]->rightanswer);
                if (in_array($answer_string, $possible_answers)) {
                    $question_answer['rate'] = $question[0]->rate;
                } else {
                    $question_answer['rate'] = 0;
                }
            } else if ($question[0]->type == 'test-one') {
                if ($question[0]->rightanswer == $answer_string) {
                    $question_answer['rate'] = $question[0]->rate;
                } else {
                    $question_answer['rate'] = 0;
                }
            } else if ($question[0]->type == 'test-multi') {
                $possible_answers = explode(';', $question[0]->rightanswer);

                $is_right = true;

                $j = 0;

                foreach ($user_answer as $ua) {
                    if (!in_array($ua, $possible_answers)) {
                        $is_right = false;
                        break;
                    } else {
                        $j++;
                    }
                }

                if ($j != sizeof($possible_answers)) {
                    $is_right = false;
                }

                if ($is_right) {
                    $question_answer['rate'] = $question[0]->rate;
                } else {
                    $question_answer['rate'] = 0;
                }
            }

            if ($your_answer) {
                $this->Tests_model->update_answer($question_answer);
            } else {
                $this->Tests_model->save_answer($question_answer);
            }
        }

        $your_answer = $this->Tests_model->get_answer($attempt_id, $question[0]->id);

        if ($your_answer) {
            if ($question[0]->type == 'test-one' || $question[0]->type == 'short') {
                $this->data['your_answer'] = $your_answer->answer;
            } else {
                $this->data['your_answer'] = explode(';', $your_answer->answer);
            }
        }

        $this->data['question'] = $question[0];
        $this->data['answers'] = $answers;
        $this->data['questions_in_test'] = $config['total_rows'];
        $this->data['attempt_id'] = $attempt_id;

        $this->load->view('partials/header');
        $this->load->view('partials/menu');
        $this->load->view('test_attempt', $this->data);
        $this->load->view('partials/footer');
    }

    function end_test($attempt_id = NULL) {
        if (!$attempt_id || empty($attempt_id)) {
            redirect('welcome', 'refresh');
        }

        $attempt = $this->Tests_model->test_attempt($attempt_id)->row();

        $test = $this->Tests_model->test($attempt->tests_id)->row();

        $this->data['visible_result'] = 0;

        $date = new DateTime();
        $attempt->end_time = $date->format('Y-m-d H:i:s');
        $attempt->sumary = $this->Tests_model->attempt_summary($attempt_id)->summary;

        if ($this->Tests_model->end_test($attempt)) {
            $this->session->unset_userdata('test_questions');
            $this->session->unset_userdata('attempt_id');
            delete_cookie('test_attempt');
        }

        if ($test->visible_result == 1) {
            $this->data['visible_result'] = 1;
            $this->data['attempt_summary'] = $attempt->sumary;
            $this->data['max_attempt_summary'] = $this->Tests_model->rate_summary($test->id)->summary;
            $this->data['attempt_summary_percentage'] = round(($attempt->sumary / $this->data['max_attempt_summary']) * 100,2);

            $questions = array();
            
            $questions = $this->Tests_model->test_questions_attempt($test->id)->result();
             

            //ładujemy biblioteke paginacji
            $this->load->library('pagination');

            // Określamy początkowy adres url dla paginacji
            $config['base_url'] = site_url('test/end_test/' . $attempt_id);
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
                    if(isset($your_answer->answer)){
                        $user_answer = explode(';',$your_answer->answer);
                    }else{
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
        }

        $this->data['test'] = $test;

        $this->load->view('partials/header');
        $this->load->view('partials/menu');
        $this->load->view('end_test', $this->data);
        $this->load->view('partials/footer');
    }

}
