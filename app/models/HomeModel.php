<?php
class HomeModel extends Model{
    protected $_table = 'products';

    public function getList() {
        $data = [
            't1',
            't2',
            't3'
        ];
        return $data;
    }

    public function getDetail($id) {
        $data = [
            't1',
            't2',
            't3'
        ];
        return $data[$id];
    }
}