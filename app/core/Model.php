<?php

abstract class Model {
    protected static $table;
	// protected 

    // Devuelve una instancia del QueryBuilder para la tabla asociada al modelo
    public static function query(string $alias = null) {
		$table = $alias ? static::$table." $alias" : static::$table;
        return new QueryBuilder($table);
    }

	public static function all() {
		return static::query()->get();
	}

    public static function find($id) {
        return static::query()->where('id', '=', $id)->first();
    }

	public static function where($column, $operator, $value) {
		return static::query()->where($column, $operator, $value);
	}

	public static function create(array $data) {
        return static::query()->insert($data);
    }

	public static function updateBy($column, $operator, $value, array $data) {
		return static::query()->where($column, $operator, $value)->update($data);
	}

    public static function updateById($id, array $data) {
		return static::query()->where('id', '=', $id)->update($data);
    }

	public static function deleteBy($column, $operator, $value) {
		return static::query()->where($column, $operator, $value)->delete();
	}
	
	public static function deleteById($id) {
		return static::query()->where('id', '=', $id)->delete();
	}
}