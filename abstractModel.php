<?php

class abstractModel
{
    const DATA_TYPE_BOOL = PDO::PARAM_BOOL;
    const DATA_TYPE_STR = PDO::PARAM_STR;
    const DATA_TYPE_INT = PDO::PARAM_INT;
    const DATA_TYPE_DECIMAL = 4;

    protected function prepareValues() {}

    private static function buildNameParametersSQL()
    {
        $namedParams = '';
        foreach (static::$tableSchema as $columnName => $type) {
            $namedParams .= $columnName . ' = :' . $columnName . ', ';
        }
        return trim($namedParams, ', ');
    }

    public function create()
    {

        global $connection;

        $sql = 'INSERT INTO ' . static::$tableName . ' SET ' . self::buildNameParametersSQL();
        $stmt = $connection->prepare($sql);

        foreach (static::$tableSchema as $columnName => $type) {
            if ($type == 4) {
                $sanitizedValue = filter_var($this->$columnName, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $stmt->bindParam(":{$columnName}", $this->$columnName);
            } else {
                $stmt->bindParam(":{$columnName}", $this->$columnName, $type);
            }
            $stmt->execute();
        }
    }
}
