<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_base extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    function categories() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $this->data['message'] = $this->session->flashdata('message');
        $this->data['error_message'] = $this->session->flashdata('error_message');

        $this->data['categories'] = $this->Tests_model->categories()->result();

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('categories', $this->data);
        $this->load->view('partials/footer');
    }

    function create_category() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        //validate form input
        $this->form_validation->set_rules('category_name', 'NAZWA KATEGORII', 'required');

        if ($this->form_validation->run() == TRUE) {

            if ($this->Tests_model->create_category($this->input->post('category_name'))) {
                $this->session->set_flashdata('message', 'Kategoria została utworzona');
                redirect("questions_base/categories", 'refresh');
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się stworzyc kategorii');
                redirect("questions_base/create_category", 'refresh');
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

            $this->data['category_name'] = array(
                'name' => 'category_name',
                'id' => 'category_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('category_name'),
                'class' => 'form-control',
            );

            $this->load->view('partials/header');
            if ($this->ion_auth->is_admin()) {
                $this->load->view('partials/admin_menu');
            } else {
                $this->load->view('partials/user_menu');
            }
            $this->load->view('create_category', $this->data);
            $this->load->view('partials/footer');
        }
    }

    function edit_category($id = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/categories', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $category = $this->Tests_model->category($id)->row();

        //validate form input
        $this->form_validation->set_rules('category_name', 'NAZWA KATEGORII', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $category_update = $this->Tests_model->update_category($id, $_POST['category_name']);

                if ($category_update) {
                    $this->session->set_flashdata('message', 'Kategoria została zmieniona');
                } else {
                    $this->session->set_flashdata('errorMessage', $this->Tests_model->errors());
                }
                redirect("questions_base/categories", 'refresh');
            }
        }


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
        //pass the user to the view
        $this->data['category'] = $category;

        $this->data['category_name'] = array(
            'name' => 'category_name',
            'id' => 'category_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('category_name', $category->name),
            'class' => 'form-control'
        );

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('edit_category', $this->data);
        $this->load->view('partials/footer');
    }

    function delete_category($id = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/categories', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_category($id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }

    function questions($category = NULL) {
        unset($_SESSION['question_answers']);

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($category != NULL && $category != 0) {
            $this->data['questions'] = $this->Tests_model->questions($category)->result();
        } else {
            $this->data['questions'] = $this->Tests_model->questions()->result();
        }

        //print_r($this->data['questions']);

        $this->data['message'] = $this->session->flashdata('message');
        $this->data['error_message'] = $this->session->flashdata('error_message');

        $this->data['categories'] = $this->Tests_model->categories()->result();

        $this->data['questions_types'] = $this->Tests_model->questions_types()->result();

        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('questions', $this->data);
        $this->load->view('partials/footer');
    }

    function delete_question($id = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/questions', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($this->Tests_model->delete_question($id)) {
            $this->session->set_flashdata('message', $this->Tests_model->messages());
            echo $this->Tests_model->messages();
        } else {
            $this->session->set_flashdata('error_message', $this->Tests_model->errors());
            echo $this->Tests_model->errors();
        }
    }

    function question_type() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $question_type = $_POST['questionType'];

        redirect('questions_base/create_question/' . $question_type, 'refresh');
    }

    function create_question($type = null) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        if ($type != null) {
            $question_type = $type;
        } else {
            $question_type = $_POST['questionType'];
        }

        //validate form input
        $this->form_validation->set_rules('question_category', 'KATEGORIA', 'required');
        $this->form_validation->set_rules('question_name', 'NAZWA PYTANIA', 'required');
        $this->form_validation->set_rules('question_content', 'TREŚĆ PYTANIA', 'required');
        $this->form_validation->set_rules('question_rate', 'PUNKTACJA', 'required|is_natural_no_zero');

        if ($question_type == 'test-multi' || $question_type == 'test-one') {
            $this->form_validation->set_rules('question_answer[0]', 'ODPOWIEDŹ 1', 'required');
            $this->form_validation->set_rules('question_answer[1]', 'ODPOWIEDŹ 2', 'required');
            $this->form_validation->set_rules('question_answer[2]', 'ODPOWIEDŹ 3', 'required');
            if (isset($_SESSION['question_answers'])) {
                for ($i = 3; $i < sizeof($_SESSION['question_answers']); $i++) {
                    $this->form_validation->set_rules('question_answer[' . $i . ']');
                }
            }

            if ($question_type == 'test-multi') {
                $this->form_validation->set_rules('question_rightanswer[]', 'PRAWIDŁOWE ODPOWIEDZI', 'required');
            } else {
                $this->form_validation->set_rules('question_rightanswer', 'PRAWIDŁOWA ODPOWIEDŹ', 'required');
            }
        } else {
            $this->form_validation->set_rules('question_answer[0]', 'ODPOWIEDŹ 1', 'required');
            if (isset($_SESSION['question_answers'])) {
                for ($i = 1; $i < sizeof($_SESSION['question_answers']); $i++) {
                    $this->form_validation->set_rules('question_answer[' . $i . ']');
                }
            }
        }

        if ($this->form_validation->run() == TRUE && !isset($_POST['add_answer'])) {

            unset($_SESSION['question_answers']);

            $answers = $this->input->post('question_answer');

            $answers_string = '';

            if ($question_type == 'short') {
                $i = 0;
                foreach ($answers as $answer) {
                    if (trim($answer) != '') {
                        if ($i == 0) {
                            $answers_string .= trim($answer);
                        } else {
                            $answers_string .= ';' . trim($answer);
                        }
                        $i++;
                    }
                }
            } else if ($question_type == 'test-multi') {
                $i = 0;

                $question_rightanswer = $this->input->post('question_rightanswer');

                foreach ($question_rightanswer as $id) {

                    if ($i == 0) {
                        $answers_string .= trim($answers[$id]);
                    } else {
                        $answers_string .= ';' . trim($answers[$id]);
                    }
                    $i++;
                }
            } else if ($question_type == 'test-one') {
                $question_rightanswer = $this->input->post('question_rightanswer');
                $answers_string = trim($answers[$question_rightanswer]);
            } else {
                $this->session->set_flashdata('message', "Musisz wybrać typ pytania");
                redirect('questions_base/questions', 'refresh');
            }

            $random = FALSE;

            if (!is_null($this->input->post('random_answers'))) {
                $random = TRUE;
            } else {
                $random = FALSE;
            }

            $question = array(
                'category_id' => $this->input->post('question_category'),
                'type' => $question_type,
                'name' => $this->input->post('question_name'),
                'question' => $this->input->post('question_content'),
                'rightanswer' => $answers_string,
                'rate' => $this->input->post('question_rate'),
                'random_answers' => $random
            );

            $created = false;

            if ($question_type == 'short') {
                $created = $this->Tests_model->create_question($question);
            } else {
                $created = $this->Tests_model->create_question($question, $answers);
            }

            if ($created) {
                $this->session->set_flashdata('message', 'Pytanie zostało utworzone');
                redirect("questions_base/questions", 'refresh');
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się utworzyć pytania');
                redirect("questions_base/create_question", 'refresh');
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
                    $this->data['message'] = $this->Tests_model->messages();
                }
            }
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//                $this->data['question_type'] = array(
//                    'name' => 'questionType',
//                    'id' => 'questionType',
//                    'type' => 'hidden',
//                    'value' => $this->form_validation->set_value('questionType'),
//                    'class' => 'form-control',
//                );

            $categories = $this->Tests_model->categories()->result();

            $this->data['question_category'] = array();

            foreach ($categories as $category) {
                $this->data['question_category'][$category->id] = $category->name;
            }

            $this->data['question_name'] = array(
                'name' => 'question_name',
                'id' => 'question_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('question_name'),
                'class' => 'form-control'
            );

            $this->data['question_content'] = array(
                'name' => 'question_content',
                'id' => 'question_content',
                'value' => $this->form_validation->set_value('question_content'),
                'class' => 'form-control'
            );


            $this->data['question_rate'] = array(
                'name' => 'question_rate',
                'id' => 'question_rate',
                'type' => 'text',
                'value' => $this->form_validation->set_value('question_rate', '1'),
                'class' => 'form-control'
            );


            if (!isset($_SESSION['question_answers'])) {

                if ($question_type == 'test-multi' || $question_type == 'test-one') {
                    $this->data['question_answers'] = array(
                        array(
                            'name' => 'question_answer[0]',
                            'id' => 'question_answer_0',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('question_answer[0]'),
                            'class' => 'form-control'
                        ),
                        array(
                            'name' => 'question_answer[1]',
                            'id' => 'question_answer_1',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('question_answer[1]'),
                            'class' => 'form-control'
                        ),
                        array(
                            'name' => 'question_answer[2]',
                            'id' => 'question_answer_2',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('question_answer[2]'),
                            'class' => 'form-control'
                        )
                    );
                } else {
                    $this->data['question_answers'] = array(
                        array(
                            'name' => 'question_answer[0]',
                            'id' => 'question_answer_0',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('question_answer[0]'),
                            'class' => 'form-control'
                        )
                    );
                }

                $_SESSION['question_answers'] = $this->data['question_answers'];
            } else {
                $this->data['question_answers'] = $_SESSION['question_answers'];

                for ($i = 0; $i < sizeof($this->data['question_answers']); $i++) {
                    //echo $this->data['question_answers'][$i]['value'];
                    $this->data['question_answers'][$i]['value'] = $this->form_validation->set_value('question_answer[' . $i . ']');
                }
            }

            if (isset($_POST['add_answer'])) {
                $this->data['question_answers'][] = array(
                    'name' => 'question_answer[' . sizeof($this->data['question_answers']) . ']',
                    'id' => 'question_answer_' . sizeof($this->data['question_answers']),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'form-control'
                );

                $_SESSION['question_answers'] = $this->data['question_answers'];
            }

            if ($question_type == 'test-multi' || $question_type == 'test-one') {



                $this->data['question_rightanswer'] = array();

                for ($i = 0; $i < sizeof($this->data['question_answers']); $i++) {
                    //echo $this->data['question_answers'][$i]['value'];
                    $this->data['question_rightanswer'][$i] = 'Odpowiedź ' . ($i + 1);
                }

                $checked = FALSE;

                if (!is_null($this->input->post('random_answers'))) {
                    $checked = TRUE;
                }

                $this->data['random_answers'] = array(
                    'name' => 'random_answers',
                    'id' => 'random_answers',
                    'type' => 'checkbox',
                    'checked' => $checked,
                    'data-size' => 'small',
                    'data-toggle' => 'toggle',
                    'data-on' => 'Tak',
                    'data-off' => 'Nie',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger'
                );
            }

            $this->data['question_type'] = $question_type;

            $this->load->view('partials/header');
            if ($this->ion_auth->is_admin()) {
                $this->load->view('partials/admin_menu');
            } else {
                $this->load->view('partials/user_menu');
            }
            $this->load->view('create_question', $this->data);
            $this->load->view('partials/footer');
        }
    }

    function edit_question($id = NULL, $from = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/questions', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $question = $this->Tests_model->question($id)->row();
        $answers = array();

        if ($question->type == 'test-one' || $question->type == 'test-multi') {
            $answers_from_database = $this->Tests_model->answers($id)->result();
            $answers = array();
            foreach ($answers_from_database as $answer) {
                $answers[] = $answer->answer;
            }
        } else {
            $answers = explode(';', $question->rightanswer);
        }

        $question_type = $question->type;

        //validate form input
        $this->form_validation->set_rules('question_category', 'KATEGORIA', 'required');
        $this->form_validation->set_rules('question_name', 'NAZWA PYTANIA', 'required');
        $this->form_validation->set_rules('question_content', 'TREŚĆ PYTANIA', 'required');
        $this->form_validation->set_rules('question_rate', 'PUNKTACJA', 'required|is_natural_no_zero');


        if ($question_type == 'test-multi' || $question_type == 'test-one') {
            $this->form_validation->set_rules('question_answer[0]', 'ODPOWIEDŹ 1', 'required');
            $this->form_validation->set_rules('question_answer[1]', 'ODPOWIEDŹ 2', 'required');
            $this->form_validation->set_rules('question_answer[2]', 'ODPOWIEDŹ 3', 'required');
            if (isset($_SESSION['question_answers'])) {
                for ($i = 3; $i < sizeof($_SESSION['question_answers']); $i++) {
                    $this->form_validation->set_rules('question_answer[' . $i . ']');
                }
            }
        } else {
            $this->form_validation->set_rules('question_answer[0]', 'ODPOWIEDŹ 1', 'required');
            if (isset($_SESSION['question_answers'])) {
                for ($i = 1; $i < sizeof($_SESSION['question_answers']); $i++) {
                    $this->form_validation->set_rules('question_answer[' . $i . ']');
                }
            }
        }

        if ($this->form_validation->run() == TRUE && !isset($_POST['add_answer'])) {

            //unset($_SESSION['question_answers']);

            $answers_form = $this->input->post('question_answer');

            $answers_string = '';

            if ($question_type == 'short') {
                $i = 0;
                foreach ($answers_form as $answer) {
                    if (trim($answer) != '') {
                        if ($i == 0) {
                            $answers_string .= trim($answer);
                        } else {
                            $answers_string .= ';' . trim($answer);
                        }
                        $i++;
                    }
                }
            } else if ($question_type == 'test-multi') {
                $i = 0;

                $question_rightanswer = $this->input->post('question_rightanswer');

                foreach ($question_rightanswer as $idq) {

                    if ($i == 0) {
                        $answers_string .= trim($answers_form[$idq]);
                    } else {
                        $answers_string .= ';' . trim($answers_form[$idq]);
                    }
                    $i++;
                }
            } else if ($question_type == 'test-one') {
                $question_rightanswer = $this->input->post('question_rightanswer');
                $answers_string = trim($answers_form[$question_rightanswer]);
            } else {
                $this->session->set_flashdata('message', "Musisz wybrać typ pytania");
                
                //$this->data['back'] = '';
        
                if(!empty($from)){
                    redirect('tests/test/'.$from, 'refresh');
                }else{              
                    redirect('questions_base/questions', 'refresh');
                }
            }

            $random = FALSE;

            if (!is_null($this->input->post('random_answers'))) {
                $random = TRUE;
            } else {
                $random = FALSE;
            }

            $question_database = array(
                'category_id' => $this->input->post('question_category'),
                'type' => $question_type,
                'name' => $this->input->post('question_name'),
                'question' => $this->input->post('question_content'),
                'rightanswer' => $answers_string,
                'rate' => $this->input->post('question_rate'),
                'random_answers' => $random
            );

            $created = false;
            if ($question_type == 'short') {
                $created = $this->Tests_model->update_question($id, $question_database);
            } else {
                $created = $this->Tests_model->update_question($id, $question_database, $answers_form);
            }

            if ($created) {
                $this->session->set_flashdata('message', 'Pytanie zostało zmienione');
                if(!empty($from)){
                    redirect('tests/test/'.$from, 'refresh');
                }else{              
                    redirect('questions_base/questions', 'refresh');
                }
            } else {
                $this->session->set_flashdata('errorMessage', 'Nie udało się zapisac zmian');
                if(!empty($from)){
                    redirect('tests/test/'.$from, 'refresh');
                }else{              
                    redirect('questions_base/questions', 'refresh');
                }
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
                    $this->data['message'] = $this->Tests_model->messages();
                }
            }
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//                $this->data['question_type'] = array(
//                    'name' => 'questionType',
//                    'id' => 'questionType',
//                    'type' => 'hidden',
//                    'value' => $this->form_validation->set_value('questionType'),
//                    'class' => 'form-control',
//                );

            $categories = $this->Tests_model->categories()->result();
            $this->data['question_category_selected'] = $question->category_id;

            $this->data['question_category'] = array();

            foreach ($categories as $category) {
                $this->data['question_category'][$category->id] = $category->name;
            }

            $this->data['question_name'] = array(
                'name' => 'question_name',
                'id' => 'question_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('question_name', $question->name),
                'class' => 'form-control'
            );

            $this->data['question_content'] = array(
                'name' => 'question_content',
                'id' => 'question_content',
                'value' => $this->form_validation->set_value('question_content', $question->question),
                'class' => 'form-control'
            );


            $this->data['question_rate'] = array(
                'name' => 'question_rate',
                'id' => 'question_rate',
                'type' => 'text',
                'value' => $this->form_validation->set_value('question_rate', $question->rate),
                'class' => 'form-control'
            );


            if (!isset($_SESSION['question_answers'])) {

                $this->data['question_answers'] = array();
                $i = 0;
                foreach ($answers as $answer) {
                    $this->data['question_answers'][$i] = array(
                        'name' => 'question_answer[' . $i . ']',
                        'id' => 'question_answer_' . $i,
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('question_answer[' . $i . ']', $answer),
                        'class' => 'form-control'
                    );
                    $i++;
                }


                $_SESSION['question_answers'] = $this->data['question_answers'];
            } else {
                $this->data['question_answers'] = $_SESSION['question_answers'];

                for ($i = 0; $i < sizeof($this->data['question_answers']); $i++) {

                    //$this->data['question_answers'][$i]['value'] = $this->form_validation->set_value('question_answer[' . $i . ']',$answers[$i]);
                    $this->data['question_answers'][$i]['value'] = $this->form_validation->set_value('question_answer[' . $i . ']');
                }
            }

            if (isset($_POST['add_answer'])) {
                $this->data['question_answers'][] = array(
                    'name' => 'question_answer[' . sizeof($this->data['question_answers']) . ']',
                    'id' => 'question_answer_' . sizeof($this->data['question_answers']),
                    'type' => 'text',
                    'value' => '',
                    'class' => 'form-control'
                );

                $_SESSION['question_answers'] = $this->data['question_answers'];
            }

            if ($question_type == 'test-multi' || $question_type == 'test-one') {
                $this->data['question_rightanswer'] = array();

                for ($i = 0; $i < sizeof($this->data['question_answers']); $i++) {
                    //echo $this->data['question_answers'][$i]['value'];
                    $this->data['question_rightanswer'][$i] = 'Odpowiedź ' . ($i + 1);
                }

                $this->data['question_rightanswer_selected'] = array();

                $rightanswers = explode(';', $question->rightanswer);

                foreach ($rightanswers as $rightanswer) {
                    for ($i = 0; $i < sizeof($answers); $i++) {
                        if ($rightanswer == $answers[$i]) {
                            $this->data['question_rightanswer_selected'][] = $i;
                        }
                    }
                }

                $checked = FALSE;

                if ($question->random_answers == 1) {
                    $checked = TRUE;
                }

                $this->data['random_answers'] = array(
                    'name' => 'random_answers',
                    'id' => 'random_answers',
                    'type' => 'checkbox',
                    'checked' => $checked,
                    'data-size' => 'small',
                    'data-toggle' => 'toggle',
                    'data-on' => 'Tak',
                    'data-off' => 'Nie',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger'
                );
            }
            
            $this->data['back'] = '';
        
            if(!empty($from)){
                $this->data['back'] = $from;
            }

            $this->data['question_type'] = $question_type;
            $this->data['question_id'] = $question->id;

            $this->load->view('partials/header');
            if ($this->ion_auth->is_admin()) {
                $this->load->view('partials/admin_menu');
            } else {
                $this->load->view('partials/user_menu');
            }
            $this->load->view('edit_question', $this->data);
            $this->load->view('partials/footer');
        }
    }

    function question($id = NULL, $from = NULL) {
        if (!$id || empty($id)) {
            redirect('questions_base/questions', 'refresh');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/logout', 'refresh');
            return show_error('Nie masz uprawnień do oglądania tej strony');
        }

        $question = $this->Tests_model->question($id)->row();
        $answers = array();

        if ($question->type == 'test-one' || $question->type == 'test-multi') {
            $answers_from_database = $this->Tests_model->answers($id)->result();
            $answers = array();
            foreach ($answers_from_database as $answer) {
                $answers[] = $answer->answer;
            }
        } else {
            $answers = explode(';', $question->rightanswer);
        }

        $this->data['question'] = $question;
        $this->data['answers'] = $answers;
        
        $this->data['back'] = '';
        
        if(!empty($from)){
            $this->data['back'] = $from;
        }


        $this->load->view('partials/header');
        if ($this->ion_auth->is_admin()) {
            $this->load->view('partials/admin_menu');
        } else {
            $this->load->view('partials/user_menu');
        }
        $this->load->view('question', $this->data);
        $this->load->view('partials/footer');
    }

}
