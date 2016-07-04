<?php
$field_id = isset($_GET['field_id']) ? $_GET['field_id'] : '';
$html_attr = isset($_GET['html_attr']) ? $_GET['html_attr'] : '';
$values = isset($_GET['values']) ? $_GET['values'] : '';

Templates::Assign('html_attr', $html_attr);
Templates::Assign('values', $values);
Templates::Assign('field_id', $field_id);
Templates::Display('accessdomain.html');


