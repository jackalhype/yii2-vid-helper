<?php

namespace common\models\database;

/**
 * Class Images, класс файлов картинки. (разных разрешений и пр)
 * @package common\models\database
 */
class Images extends AppActiveRecord
{

    public static function tableName() {
        return 'images';
    }

    public function rules() {
        return [
            ['type', 'default', 'value' => 'default'],
            [['img_id', 'type', 'resolution', 'file_size', 'path'], 'safe'],
        ];
    }

    /**
     * @param $type string
     * @return $this
     */
    public function setType($type = 'default') {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $img Img | array
     */
    public function createPath($img) {
        if (empty($this->type)) {
            $this->setType();
        }
        $rndhash = md5('oiqfjoif94243uih' . $this->orig_filename);

        if (!is_array($img)) {
            $path = '' . $img->entity_type . '/'
                . $img->entity_id . '/'
                .$img->id . '-' . $this->type . '-' . $rndhash . $this->orig_fileext;
        } else {
            $path = '' . $img['entity_type'] . '/'
                . $img['entity_id'] . '/'
                .$img['id'] . '-' . $this->type . '-' . $rndhash . $this->orig_fileext;
        }

    }

}