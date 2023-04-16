<?php
    $type = 'product';
    $modelName = 'Product';

    if (empty($params)) {
        require 'items/itemList.php';
    } else {
        require 'items/itemContent.php';
    }
    