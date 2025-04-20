<?php

class Database
{
    private $db;

    public function __construct($path)
    {
        $this->db = new SQLite3($path);
    }

    public function Execute($sql)
    {
        return $this->db->exec($sql);
    }

    public function Fetch($sql)
    {
        $result = $this->db->query($sql);
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function Create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map(function ($value) {
            return "'" . SQLite3::escapeString($value) . "'";
        }, array_values($data)));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->Execute($sql);
        return $this->db->lastInsertRowID();
    }

    public function Read($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = $id";
        $result = $this->db->query($sql);
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function Update($table, $id, $data)
    {
        $set = implode(", ", array_map(function ($key, $value) {
            return "$key = '" . SQLite3::escapeString($value) . "'";
        }, array_keys($data), array_values($data)));
        $sql = "UPDATE $table SET $set WHERE id = $id";
        return $this->Execute($sql);
    }

    public function Delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = $id";
        return $this->Execute($sql);
    }

    public function Count($table)
    {
        $sql = "SELECT COUNT(*) as count FROM $table";
        $result = $this->db->query($sql);
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row['count'];
    }
}