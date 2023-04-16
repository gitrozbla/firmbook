<?php
    $type = 'service';
    $modelName = 'Service';

    if (empty($params)) {
        require 'items/itemList.php';
    } else {
        require 'items/itemContent.php';
    }
    