<?php

class DbUtil{
    public static function insert($db, $tableName, $insertSqlArr, $returnId = false, $replace = false){
        $insertKeySql = $insertValueSql = $comma = '';
        $insertValues = array();
        $count = 1;
        foreach ($insertSqlArr as $insertKey => $insertValue) {
            $insertKeySql .= $comma.'`'.$insertKey.'`';
            $insertValueSql .= $comma.'?';
            $insertValues[$count] = $insertValue;
            $comma = ', ';
            $count++;
        }
        $method = $replace ? 'REPLACE': 'INSERT';
        $command = $db->createCommand($method.' INTO `'.$tableName.'` ('.$insertKeySql.') VALUES ('.$insertValueSql.')');
        $command->bindValues($insertValues);
        $command->execute();
        if($returnId && !$replace) {
            $command = $db->createCommand('SELECT last_insert_id()');
            return $command->queryScalar();
        }
    }
    
    public static function batchInsert($db, $tableName, $columnNames, $insertDatas, $replace = false) {
        if (count($insertDatas) == 0) {
            return 0;
        }
        
        $columnCount = count($columnNames);
        $insertKeySql = implode(',', $columnNames);
        $singleBindSql = '(' . implode(',', array_fill(0, $columnCount, '?')) . ')';
        $insertValueSql = implode(',', array_fill(0, count($insertDatas), $singleBindSql));
        
        $insertValues = array();
        $count = 1;
        foreach ($insertDatas as $insertData) {
            for ($i = 0; $i < $columnCount; $i++) {
                $insertValues[$count] = $insertData[$i];
                $count++;
            }
        }
        $method = $replace ? 'REPLACE': 'INSERT';
        $command = $db->createCommand($method.' INTO `'.$tableName.'` ('.$insertKeySql.') VALUES '.$insertValueSql);
        $command->bindValues($insertValues);
        return $command->execute();
    }

    public static function update($db, $tableName, $setSqlArr, $whereSqlArr = array()){
        if(empty($setSqlArr)){
            return;
        }
        $setSql = $comma = '';
        $bindValues = array();
        $count = 1;
        foreach ($setSqlArr as $setKey => $setValue) {
            $setSql .= $comma.'`'.$setKey.'`'.'= ? ';
            $comma = ', ';
            $bindValues[$count] = $setValue;
            $count++;
        }
        $whereSql = $comma = '';
        if(empty($whereSqlArr)) {
            $whereSql = '1';
        } elseif(is_array($whereSqlArr)) {
            foreach ($whereSqlArr as $key => $value) {
                $whereSql .= $comma.'`'.$key.'`'.'= ? ';
                $comma = ' AND ';
                $bindValues[$count] = $value;
                $count++;
            }
        } else {
            $whereSql = '1';
        }
        
        $command = $db->createCommand('UPDATE `'.$tableName.'` SET '.$setSql.' WHERE '.$whereSql);
        $command->bindValues($bindValues);
        $command->execute();

    }
    
    
}