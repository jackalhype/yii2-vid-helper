<?php

namespace frontend\models;

use yii\base\Model;
use common\models\database\Node;

class Video extends Model
{
    public static $css_classes = [
        'lvl-0',
        'lvl-1',
        'lvl-2',
        'lvl-3',
        'lvl-4',
        'lvl-5',
        'lvl-6',
        'hide',
    ];


    /**
     * @param $array_tree result of Node::getFull()
     */
    public static function makeHtmlTree($array_tree) {
//        $doc = new \DOMDocument('1.0', 'utf-8');
        $dom = new \DOMDocument();
        $xpath = new \DOMXPath($dom);
        $root_node = current($array_tree);
        $ol = $dom->createElement('ol');
        self::makingHtmlTraverseArray($root_node, $ol, $dom, 0);
        $dom->appendChild($ol);
        $html = $dom->saveHTML();
//        var_dump($html); die;
        return $html;
    }


    protected static function makingHtmlTraverseArray($node, \DOMElement &$ol, \DOMDocument $dom, int $lvl=0) {
        $title = $node['title'];
        $li = $dom->createElement('li');
        $txt = $dom->createTextNode($title);
        $li->appendChild($txt);
        if (isset($node['nested'])) {
            $txt_nl = $dom->createTextNode("\n");
            $li->appendChild($txt_nl);
            $ol_nest = $dom->createElement('ol');
            foreach($node['nested'] as $id2 => $node2) {
                self::makingHtmlTraverseArray($node2, $ol_nest, $dom, $lvl+1);
            }
            $li->appendChild($ol_nest);
        }
        $ol->appendChild($li);
        if ($lvl == 0) {
            $class = 'lvl-'.$lvl;
        } else {
            $class = 'lvl-'.$lvl . ' hide';
        }
        $li->setAttribute('class', $class);
    }

    protected static function msg($val) {
        print_r($val);
        echo "\n";
    }

}
