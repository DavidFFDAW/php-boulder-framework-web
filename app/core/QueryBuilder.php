<?php
class QueryBuilder {
    protected $table;
    protected $selectColumns = '*';
    protected $whereConditions = [];
    protected $bindings = [];
    protected $orderByClause = '';
    protected $limitClause = '';
    protected $joinClauses = [];

    public function __construct($table) {
        $this->table = $table;
    }

    public function select($columns = '*') {
		$this->selectColumns = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function where($column, $operator, $value) {
        $this->whereConditions[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

	/**
	 * @param string $table
	 * @param string $firstColumn
	 * @param string $operator
	 * @param string $secondColumn
	 * @param string $joinType (default: INNER) One of INNER, LEFT, RIGHT, etc.
	 * 
	 */
    public function join($table, $firstColumn, $operator, $secondColumn, $joinType = 'INNER') {
        $joinType = strtoupper($joinType);
        $this->joinClauses[] = "$joinType JOIN $table ON $firstColumn $operator $secondColumn";
        return $this;
    }

	public function orderBy($column1, $direction1 = 'ASC') {
        $orders = [];
        $orders[] = "$column1 " . strtoupper($direction1);
        $this->orderByClause = " ORDER BY " . implode(", ", $orders);
        return $this;
    }

    public function limit($limit, $offset = null) {
        $this->limitClause = " LIMIT " . intval($limit);
        if ($offset !== null) {
            $this->limitClause .= " OFFSET " . intval($offset);
        }
        return $this;
    }

	protected function buildQuery() {
        $sql = "SELECT {$this->selectColumns} FROM {$this->table}";
        if (!empty($this->joinClauses)) {
            $sql .= " " . implode(" ", $this->joinClauses);
        }
        if (!empty($this->whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->whereConditions);
        }
        if ($this->orderByClause) {
            $sql .= $this->orderByClause;
        }
        if ($this->limitClause) {
            $sql .= $this->limitClause;
        }
        return $sql;
    }

    public function first() {
        $sql = $sql = $this->buildQuery() . " LIMIT 1";
        // return ['sql' => $sql, 'bindings' => $this->bindings];
		$stmt = Db::getInstance()->preparedQuery($sql, $this->bindings);
		return $stmt->fetch();
    }

    public function get() {
		$sql = $this->buildQuery();
        // Aquí ejecutarías la consulta y retornarías los resultados
        // return ['sql' => $sql, 'bindings' => $this->bindings];
		$stmt = Db::getInstance()->preparedQuery($sql, $this->bindings);
		return $stmt->fetchAll();
    }

	public function insert(array $data) {
        $columns = implode(", ", array_map(fn($col) => "`$col`", array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));

        return $db->lastInsertId(); // Retorna el ID insertado
    }

    public function update(array $data) {
        if (empty($this->whereConditions)) {
            throw new Exception("No se puede actualizar sin condiciones WHERE.");
        }

        $setClause = implode(", ", array_map(fn($col) => "`$col` = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE " . implode(" AND ", $this->whereConditions);

        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $this->bindings));

        return $stmt->rowCount(); // Devuelve el número de filas afectadas
    }

	public function delete() {
		if (empty($this->whereConditions)) {
			throw new Exception("No se puede eliminar sin condiciones WHERE.");
		}

		$sql = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $this->whereConditions);

		$db = Db::getInstance()->getConnection();
		$stmt = $db->prepare($sql);
		$stmt->execute($this->bindings);

		return $stmt->rowCount(); // Devuelve el número de filas afectadas
	}
}