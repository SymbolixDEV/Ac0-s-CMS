<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Admin Control Panel - <?=$title.' '.$ui;?></title>
	<link rel="stylesheet" href="<?=base_url('content/css/acp_style.css');?>" />
	<link rel="stylesheet" href="<?=base_url('content/css/autocomplete.css');?>" />
	<script type="text/javascript" src="<?=base_url('content/js/autocomplete.js');?>"></script>
    <?=$head_content;?>
</head>
<body> 
<div class="wrapper">
    <center>
        <div class="container">
            <div class="title">
                <div><span style="float: left;">Admin Control Panel !</span>
                    <ol class="menu">
						 <a href="<?=base_url();?>"><li><<<</li></a>
                        <a href="<?=base_url('index.php/acp');?>"><li <?= ($this->uri->segment(2)=='' || $this->uri->segment(2)=='home') ? 'style="color: #000000;"' : ''; ?>>Home</li></a>
                        <a href="<?=base_url('index.php/acp/console');?>"><li <?= ($this->uri->segment(2)=='console') ? 'style="color: #000000;"' : ''; ?>>Console</li></a>
                        <a href="<?=base_url('index.php/acp/news');?>"><li <?= ($this->uri->segment(2)=='news') ? 'style="color: #000000;"' : ''; ?>>News</li></a>
                        <a href="<?=base_url('index.php/acp/realms');?>"><li <?= ($this->uri->segment(2)=='realms') ? 'style="color: #000000;"' : ''; ?>>Realms</li></a>
                        <a href="<?=base_url('index.php/acp/vote');?>"><li <?= ($this->uri->segment(2)=='vote') ? 'style="color: #000000;"' : ''; ?>>Vote Shop</li></a>
                        <a href="<?=base_url('index.php/acp/donate');?>"><li <?= ($this->uri->segment(2)=='donate') ? 'style="color: #000000;"' : ''; ?>>Donate Shop</li></a>
                        <a href="<?=base_url('index.php/acp/logs');?>"><li <?= ($this->uri->segment(2)=='logs') ? 'style="color: #000000;"' : ''; ?>>Logs</li></a>
                        <a href="<?=base_url('index.php/home/out');?>"><li>Logout</li></a>
                    </ol>
                    <span style="clear: both; display: block;"></span>
                </div>
            </div>
        </div>
    </center>
    <div class="body">
        <center>
            <div class="container">
                <div class="content">			