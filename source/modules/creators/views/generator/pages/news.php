<?php
    if (empty($params)) {
        require 'news/newsList.php';
    } else {
        require 'news/newsContent.php';
    }
    