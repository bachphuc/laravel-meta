<?php

function meta_title(){
    return PageMeta::getPageTitle();
}

function meta_setting($key, $default = null){
    return PageMeta::getMetaSetting($key, $default);
}

function meta_path(){
    return PageMeta::metaViewPath();
}

function meta_subject($s = null){
    if(empty($s)) return PageMeta::getSubject();

    PageMeta::setSubject($s);
}