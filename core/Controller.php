<?php
namespace Core;

class Controller {
    public function model($model) {
        $modelClass = "App\\Models\\" . $model;
        
        if(class_exists($modelClass)) {
            return new $modelClass();
        }
        
        return false;
    }

    public function render($view, $data=[]) {
        extract($data);
        if(file_exists(_DIR_ROOT.'/app/views/'.$view.'.php')){
            require_once _DIR_ROOT.'/app/views/'.$view.'.php';
        }
    }

    public function handleUpload($base_path, $upload_path, $tmp_name, $new_filename) {
        if (!file_exists($base_path . '/public')) {
            mkdir($base_path . '/public', 0777, true);
        }
        if (!file_exists($base_path . '/public/uploads')) {
            mkdir($base_path . '/public/uploads', 0777, true);
        }
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        return move_uploaded_file($tmp_name, $upload_path . $new_filename);
    }
}