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

//        print_r($rows); die;

        $n = -1;
        foreach ($rows as $r) {
            $n++;
            $parent = null;
            if (!empty($r['node_id'])) {
                if (isset($r['parent6_id'])) {
                    $res[$r['parent6_id']]['nested'][$r['parent5_id']]['nested'][$r['parent4_id']]['nested'][$r['parent3_id']]['nested'][$r['parent2_id']]['nested'][$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['parent5_id'])) {
                    $res[$r['parent5_id']]['nested'][$r['parent4_id']]['nested'][$r['parent3_id']]['nested'][$r['parent2_id']]['nested'][$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['parent4_id'])) {
                    $res[$r['parent4_id']]['nested'][$r['parent3_id']]['nested'][$r['parent2_id']]['nested'][$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['parent3_id'])) {
                    $res[$r['parent3_id']]['nested'][$r['parent2_id']]['nested'][$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['parent2_id'])) {
                    $res[$r['parent2_id']]['nested'][$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['parent_id'])) {
                    $res[$r['parent_id']]['nested'][$r['node_id']] = $r;
                } else if (isset($r['node_id'])) {
                    $res[$r['node_id']] = $r;
                    continue;
                }
            }
        }

        return $res;
    }


    /**
     * Save node tree from html ol/ul-li representation
     * @param $params
     * @return array
     */
    public function saveHtmlTree($params) {
        $id = $this->id;
        $html_tree = isset($params['html_tree']) ? $params['html_tree'] : null;
        // TODO: delete old, replace with new
        $parent_id = isset($params['parent_id']) ?
            $params['parent_id'] : !empty($this->parent_id) ?
                $this->parent_id : 1;
        $root_node = self::find()->where(['id' => $parent_id])->asArray()->one();
        if (!$root_node) {
            return [ 'success' => false, 'error' => "root node not found (id=$parent_id)" ];
        }
        $sort = isset($params['sort']) ?
            $params['sort'] : !empty($this->sort) ?
                $this->sort : null;
        if (!$sort) {
            $last_sort = self::find()->select('sort')->where(['parent_id' => $parent_id])->orderBy(['sort' => SORT_DESC])
                ->limit(1)->scalar();
            $sort = intval($last_sort) + 10;
        }

        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html_tree);
        $full_html = $doc->saveHTML();
        $xpath = new \DOMXPath($doc);
        $body = $xpath->query('//body')->item(0);
        $elems = $xpath->query("//body/*");
        $this->traverseDom($elems, $xpath, $root_node, 1, $id);

        if ($id) {
            $children = self::find()->where(['parent_id' => $id])->all();
            foreach ($children as $ch) {
                $ch->delete();      // cascade
            }
        }

        $id = $this->saveNodeTree($root_node['nested'], $root_node['id'], $sort);

        return [ 'success' => true, 'node_id' => $id ];
    }

    protected function traverseDom($oluls, $xpath, &$parent_node, $limit = null, $id = null) {
        $cou = 0;
        foreach($oluls as $el) {
            $cou +=1;
            $olul = strtolower($el->tagName);
            $lis = $xpath->query('./li', $el);
            $cl = -1;
            foreach($lis as $li) {
                $cl +=1;
                $txt_nodes = $xpath->query('./text()', $li);
                $ols = $xpath->query('./'.$olul, $li);
                # $imgs = $xpath->query('./img', $li);
                $title = '';
                foreach($txt_nodes as $tn) {
                    $txt = trim($tn->nodeValue);
                    $title .= $txt;
                }
                $item = [
                    'title' => $title,
                    'descr' => '',
                ];
                if ($id) {
                    $item['id'] = $id;
                }
                if (!isset($parent_node['nested'])) {
                    $parent_node['nested'] = [];
                }
                $parent_node['nested'][$cl] = $item;

                if (count($ols)) {
                    $this->traverseDom($ols, $xpath, $parent_node['nested'][$cl]);
                }
            }
            if ($limit) {
                if ($cou >= $limit) {
                    break;
                }
            }
        }
    }

    /**
     * @param $node_tree array
     * @param int $parent_id
     * @throws \Exception
     */
    public function saveNodeTree($node_tree, $parent_id = 1, $sort_expl = null) {
        foreach ($node_tree as $k => $node) {
            $sort = $sort_expl ? $sort_expl : ($k + 1) * 10;
            $id = isset($node['id']) ? $node['id'] : null;
            if ($id) {
                $model = self::findOne(['id' => $id]);
            } else {
                $model = new self();
            }
            if (!isset($node['title'])) {
                throw new \Exception('Node::saveNodeTree() null title');
            }
            $model->title = isset($node['title']) ? $node['title'] : null;
            $model->descr = isset($node['descr']) ? $node['descr'] : '';
            $model->sort = $sort;
            $model->status = 1;
            $model->parent_id = $parent_id;
            if (!$model->save()) {
                throw new \Exception("Unable to save Node");
            }
            if (isset($node['nested'])) {
                $this->saveNodeTree($node['nested'], $model->id);
            }
        }
        return $model->id;
    }


    protected static function msg($val) {
        print_r($val);
        echo "\n";
    }


}