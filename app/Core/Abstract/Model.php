<?php

namespace PopupBox\Core\Abstract;

use PopupBox\Core\Interfaces\ModelInterface;

// abstract model
abstract class Model implements ModelInterface{
    protected static $table;
    protected static $id = 'ID'; // default primary key
    protected static $field = []; // field
    protected static $delete_id = 'ID';
    protected static $join_key;

    // get all rows with optional conditions
    public static function lists($where = '', $join = [], $limit = 0) {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}" . static::$table;
        if (!empty($join)) {
            $index = 0;
            foreach ($join as $key => $value) {
                $query .= " {$value[0]} JOIN {$key} ON {$key}.{$value[1]} = {$wpdb->prefix}" . static::$table . "." . static::$join_key[$index];
                $index += 1;
            }
        }
        if (!empty($where)) {
            $query .= " WHERE {$where}";
        }
        if ($limit > 0) {
            $query .= " LIMIT {$limit}";
        }
        return $wpdb->get_results($query, ARRAY_A);
    }

    // get single row by ID
    public static function show($id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}".static::$table." WHERE ".static::$id." = %d", $id);
        return $wpdb->get_row($query, ARRAY_A);
    }

    // create new record
    public static function create($data) {
        global $wpdb;
        $data = static::filter_fields($data);
        $wpdb->insert($wpdb->prefix . static::$table, $data);
        return $wpdb->insert_id;
    }

    // update record by ID
    public static function update($id, $data) {
        global $wpdb;
        $data = static::filter_fields($data);
        return $wpdb->update($wpdb->prefix . static::$table, $data, [static::$id => $id]);
    }

    // delete record by ID
    public static function delete($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . static::$table, [static::$delete_id => $id]);
    }

    // field permission
    protected static function filter_fields($data) {
        return array_filter(
            $data,
            fn($key) => in_array($key, static::$field),
            ARRAY_FILTER_USE_KEY
        );
    }
}