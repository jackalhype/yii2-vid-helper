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
        $res = [];
        if (!$id) {
            return $res;
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

        $n = -1;
        foreach ($rows as $r) {
            $n++;
            $parent = null;
            if (!empty($r['parent_id'])) {
                if (isset($r['parent6_id'])) {
                    $parent =& $res[$r['parent6_id']][$r['parent5_id']][$r['parent4_id']][$r['parent3_id']][$r['parent2_id']][$r['parent_id']];
                } else if (isset($r['parent5_id'])) {
                    $parent =& $res[$r['parent5_id']][$r['parent4_id']][$r['parent3_id']][$r['parent2_id']][$r['parent_id']];
                } else if (isset($r['parent4_id'])) {
                    $parent =& $res[$r['parent4_id']][$r['parent3_id']][$r['parent2_id']][$r['parent_id']];
                } else if (isset($r['parent3_id'])) {
                    $parent =& $res[$r['parent3_id']][$r['parent2_id']][$r['parent_id']];
                } else if (isset($r['parent2_id'])) {
                    $parent =& $res[$r['parent2_id']][$r['parent_id']];
                } else if (isset($r['parent_id'])) {
                    $parent =& $res[$r['parent_id']];
                } else if (isset($r['node_id'])) {
                    $res[$r['node_id']] = $r;
                    continue;
                }
                if (!isset($parent['nested'])) {
                    $parent['nested'] = [];
                }
                $parent['nested'][] = $r;
            }
        }

        return $res;
    }


    public function saveTree($params) {
        $id = $this->id;
        $html_tree = isset($params['html_tree']) ? $params['html_tree'] : null;
        // TODO: delete old, replace with new

        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html_tree);
        $full_html = $doc->saveHTML();
        $xpath = new \DOMXPath($doc);
        $body = $xpath->query('//body')->item(0);
        $elems = $xpath->query("//body/*");
        echo '<pre>';
        $this->traverse($elems, $xpath, &$node_tree);
        foreach($elems as $el) {
//            if ($el->hasChildNodes()) {
//                $childNodes = $el->childNodes;
//                foreach($childNodes as $ch) {
//                    self::msg($ch);
//                }
//            }
            $olul = strtolower($el->tagName);
            $lis = $xpath->query('./li', $el);
            foreach($lis as $li) {
//                self::msg($li);
                $txt_nodes = $xpath->query('./text()', $li);
                $ols = $xpath->query('./'.$olul, $li);
                # $imgs = $xpath->query('./img', $li);
                foreach($txt_nodes as $tn) {
                    $txt = $tn->nodeValue;
                    self::msg($txt);
                }
                $this->traverse($ols, $xpath);
            }

        }


        return [ 'success' => true ];
    }

    protected function traverse($oluls, $xpath, &$node_tree) {

    }

    protected static function msg($val) {
        print_r($val);
        echo "\n";
    }


}