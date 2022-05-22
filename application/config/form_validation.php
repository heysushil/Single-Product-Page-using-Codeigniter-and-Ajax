<?php

defined('BASEPATH') or exit('no direct script allowed');

$config = array(
    'add_new_product_form_data' => array(
        array(
            'field' => 'name',
            'label' => 'name',
            'rules' => 'required',
        ), 
        array(
            'field' => 'brand_name',
            'label' => 'brand_name',
            'rules' => 'required',
        ), 
        array(
            'field' => 'price',
            'label' => 'price',
            'rules' => 'required',
        ), 
        array(
            'field' => 'qnt',
            'label' => 'qnt',
            'rules' => 'required',
        ),         
    ),
);