<?php
namespace PopupBox\Core\Interfaces;

// model interface
interface ModelInterface {
    public static function lists($where = '', $limit = 0);
    public static function show($id);
    public static function create($items);
    public static function update($id, $items);
    public static function delete($id);
}