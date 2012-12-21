<?php

$data['title'] = $title;
$data['ui'] = $ui;
$data['left_content'] = $left_content;
$data['right_content'] = $right_content;
$data['head_content'] = $this->auto->head_content();
$this->load->view('acp/template/header', $data);
$this->load->view('acp/content/left', $data);
$this->load->view('acp/content/right', $data);
$this->load->view('acp/template/footer');

?>
     