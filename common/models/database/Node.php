<?php

namespace common\models\database;

use yii\db\ActiveRecord;
use yii\db\conditions\OrCondition;
use yii\helpers\ArrayHelper;

class Node extends AppActiveRecord
{
    public static function tableName() {
        return 'node';
    }

    public static function getFull($params=[]) {
        $id = isset($params['id']) ? $params['id'] : null;
        if (!$id) {
            return [];
        }

        $rows = static::find()
            ->select([
                'n6.parent_id as parent6_id',
                'n5.parent_id as parent5_id',
                'n4.parent_id as parent4_id',
                'n3.parent_id as parent3_id',
                'n2.parent_id as parent2_id',
                'n1.parent_id as parent_id',
                'n1.id as node_id',
                'n1.sort',
                'n1.title',
                'n1.descr',
                'n1.status',
            ])->from('node as n1')
            ->leftJoin('node as n2', 'n2.id = n1.parent_id')
            ->leftJoin('node as n3', 'n3.id = n2.parent_id')
            ->leftJoin('node as n4', 'n4.id = n3.parent_id')
            ->leftJoin('node as n5', 'n5.id = n4.parent_id')
            ->leftJoin('node as n6', 'n6.id = n5.parent_id')
            ->andWhere(new OrCondition([
                [ 'n1.id' => $id ],
                [ 'n1.parent_id' => $id ],
                [ 'n2.parent_id' => $id ],
                [ 'n3.parent_id' => $id ],
                [ 'n4.parent_id' => $id ],
                [ 'n5.parent_id' => $id ],
                [ 'n6.parent_id' => $id ],
            ]))->orderBy([
                'parent6_id' => SORT_ASC,
                'parent5_id' => SORT_ASC,
                'parent4_id' => SORT_ASC,
                'parent3_id' => SORT_ASC,
                'parent2_id' => SORT_ASC,
                'parent_id' => SORT_ASC,
                'n1.sort' => SORT_ASC,
            ])->asArray()->all();

        return $rows;
    }


}