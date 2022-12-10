<?php

function makeTable($data, $showHeader = true) {
    $tableStr = "";

    $tableStr .= "<table>";

    foreach($data as $row) {
        if ($showHeader) {
            $tableStr .= "<tr>";
            foreach($row as $columnName => $columnValue) {
                $tableStr .= sprintf("<th>%s</th>", $columnName);
            }
            $tableStr .= "</tr>";
            $showHeader = false;
        }
        $tableStr .= "<tr>";
        foreach($row as $columnName => $columnValue) {
            $tableStr .= sprintf("<td>%s</td>", $columnValue);
        }
        $tableStr .= "</tr>";
    }

    $tableStr .= "</table>";

    return $tableStr;
}

?>