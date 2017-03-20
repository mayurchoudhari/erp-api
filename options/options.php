<?php

// class to create query filters
class Options {

    function filter($filters = []) {
        $filterData = [];
        foreach ($filters as $filter) {
            $filter = explode(',',$filter);
            switch ($filter[1]) {
                case "eq":  // equal to (=)
                    $filterData[$filter[0]] = $filter[2];
                    break;
                case "ne":  // not equal to (!=)
                    $filterData[$filter[0]] = ['$ne' => $filter[2]];
                    break;
                case "gt":  // greater than (>)
                    $filterData[$filter[0]] = ['$gt' => $filter[2]];
                    break;
                case "gte":  // greater than equal to (>=)
                    $filterData[$filter[0]] = ['$gte' => $filter[2]];
                    break;
                case "lt":  // less than (<)
                    $filterData[$filter[0]] = ['$lt' => $filter[2]];
                    break;
                case "lte":  // less than equal to (<=)
                    $filterData[$filter[0]] = ['$lte' => $filter[2]];
                    break;
            }
        }
        return $filterData;
    }

    function sort($sort = []) {
        $sortData = [];
        $sort = explode(',',$sort);
        switch ($sort[1]) {
            case "ASC":  // Ascending
                $sortData[$sort[0]] = 1;
                break;
            case "DSC":  // Ascending
                $sortData[$sort[0]] = -1;
                break;
        }
        return $sortData;
    }
}

?>