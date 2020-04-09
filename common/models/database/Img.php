<?php

namespace common\models\database;

/**
 * Class Img, класс картинки как сущности.
 * @package common\models\database
 */
class Img extends AppActiveRecord
{
    protected static $images_web_path = '/images/';

    public static function tableName() {
        return 'img';
    }

    public function rules() {
        return [
            ['sort', 'default', 'value' => 99],
            [['entity_type', 'entity_id', 'sort',], 'safe'],
        ];
    }

    public function getImages(){
        return $this->hasMany(Images::class, ['img_id' => 'id'])->orderBy(['type' => SORT_ASC]);
    }

    public static function getImagesDir() {
        return \Yii::getAlias('@frontend') . '/web/images';
    }

    /**
     * @param $entity
     * @param $entity_id
     * @param array $params
     *   e.g.
     *     'replace' => true   -- will replace all images with those from $_FILES
     *     'FILES_param_name' => 'img'
     *     'image_type' => 'default'    -- type for images table
     *
     */
    public static function uploadFileImages($entity, $entity_id, $params=[]) {
        $replace = isset($params['replace']) ? $params['replace'] : false;
        $FILES_param_name = isset($params['FILES_param_name']) ? $params['FILES_param_name'] : 'img';
        $entity_type = call_user_func($entity .'::tableName');
        $image_type = isset($params['image_type']) ? $params['image_type'] : 'default';
        $image_type_filepart = $image_type !== 'default' ? $image_type : '';
        $img_id = isset($params['img_id']) ? $params['img_id'] : null;

        if (empty($entity_type)) {
            return [ 'success' => false, 'error' => 'Img::uploadFileImages() empty entity_type' ];
        }
        $entity_id = intval($entity_id);
        if (!$entity_id) {
            return [ 'success' => false, 'error' => 'Img::uploadFileImages() empty entity_id' ];
        }

        $images_dir = self::getImagesDir();
        $obj_dir = "{$images_dir}/{$entity_type}/{$entity_id}";
        if (!self::createDirIfNotExists($obj_dir)) {
            return [ 'success' => false, 'error' => 'unable to create dir '. $obj_dir ];
        }
        if ($replace) {
            self::clearEntityFiles($obj_dir);
            $res = self::deleteImagesRecords(['entity_type' => $entity_type, 'entity_id' => $entity_id ]);
            if ($res !== true) return $res;
        }
        if ($img_id) {
            $res = self::deleteImagesRecords(['id' => $img_id]);
            if ($res !== true) return $res;
        }

        // get last sort
        $max_sort = self::find()->where([
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
        ])->max('sort');
        if (!$max_sort) {
            $max_sort = 0;
        }

        $img_ids = [];
        if (!empty($_FILES[$FILES_param_name])) {
            $n = count($_FILES[$FILES_param_name]['name']);
            for ($i = 0; $i<$n; $i++) {
                $sort = $max_sort + (($i + 1) * 10);
                $name = $_FILES[$FILES_param_name]['name'][$i];
                $type = $_FILES[$FILES_param_name]['type'][$i];
                $tmp_name = $_FILES[$FILES_param_name]['tmp_name'][$i];
                $error = $_FILES[$FILES_param_name]['error'][$i];
                $size = $_FILES[$FILES_param_name]['size'][$i];
                $randomhash = self::getRandomHash();
                $file_ext = pathinfo($name, PATHINFO_EXTENSION);
                $filesize = @filesize($tmp_name);

                // create Img record
                $img = new self();
                $img->entity_type = $entity_type;
                $img->entity_id = $entity_id;
                $img->sort = $sort;
                if (!$img->save()) {
                    return [ 'success' => false, 'error' => "unable to save img"];
                }
                $img_ids[] = $img->id;

                $img_id_filepart = $img->id;
                $new_relpath = "{$entity_type}/{$entity_id}/{$img_id_filepart}-{$image_type_filepart}-{$randomhash}.{$file_ext}";
                $new_fullpath = "{$images_dir}/$new_relpath";
                // moving file
                if (!move_uploaded_file($tmp_name, $new_fullpath)) {
                    try {
                        $img->delete();
                    } catch (\Exception $e) {}
                    return [ 'success' => false, 'error' => "error on file moving {$name}" ];
                }
                // creating images record
                $image = new Images();
                $image->img_id = $img->id;
                $image->type = $image_type;
                $image->resolution = '';
                $image->file_size = $filesize;
                $image->path = $new_relpath;
                if (!$image->save()) {
                    return [ 'success' => false, 'error' => "unable to save image" ];
                }
            }
        }

        return [ 'success' => true, 'img_ids' => $img_ids ];
    }

    /**
     * Clear files and db records of the object
     * @param $entity ::class
     * @param $entity_id
     * @return array|bool
     */
    public static function deleteEntity($entity, $entity_id) {
        $entity_type = call_user_func($entity .'::tableName');
        if (empty($entity_type)) {
            return [ 'success' => false, 'error' => 'Img::deleteEntity, empty entity_type' ];
        }
        $entity_id = intval($entity_id);
        if (!$entity_id) {
            return [ 'success' => false, 'error' => 'Img::deleteEntity, empty entity_id' ];
        }
        $images_dir = self::getImagesDir();
        $obj_dir = "{$images_dir}/{$entity_type}/{$entity_id}";
        self::clearEntityFiles($obj_dir);
        $res = self::deleteImagesRecords(['entity_type' => $entity_type, 'entity_id' => $entity_id ]);
        if ($res !== true) return $res;
        return [ 'success' => true ];
    }


    public static function createDirIfNotExists($dir) {
        if (is_dir($dir)) {
            return true;
        }
        return mkdir($dir, 0755, true);
    }

    /**
     * clear contents of dir
     */
    public static function clearEntityFiles($dir) {
        self::clearDirContents($dir, $dir);
    }

    /**
     * Clear dir contents, remain parent_dir itself
     * usage: clearDirContents($dir, $dir);
     * @param $str
     * @param $parent_dir
     * @return bool
     */
    public static function clearDirContents($str, $parent_dir) {
        //It it's a file.
        if (is_file($str)) {
            //Attempt to delete it.
            return unlink($str);
        }
        //If it's a directory.
        elseif (is_dir($str)) {
            //Get a list of the files in this directory.
            $scan = glob(rtrim($str,'/').'/*');
            //Loop through the list of files.
            foreach($scan as $index=>$path) {
                //Call our recursive function.
                self::clearDirContents($path, $parent_dir);
            }
            if ($str !== $parent_dir) {
                //Remove the directory itself.
                return @rmdir($str);
            } else {
                return true;
            }
        }
    }

    /**
     * delete Images and Img, and their files;
     * @param $where array e.g. ['entity_type' => 'actors_pair', 'entity_id' => $pairs_ids]
     */
    public static function deleteImagesRecords($where) {
        if (empty($where)) {
            return [ 'success' => false, 'error' => __FUNCTION__ . ' $where param not set'];
        }
        $images_dir = self::getImagesDir();
        try {
            $imgs = self::find()->where($where)->all();
            foreach($imgs as $img) {
                if (!$img) {
                    return ['success' => false, 'error' => "img record with id={$img_id} not found"];
                }
                $images = Images::find()->where(['img_id' => $img->id])->all();
                foreach ($images as $image) {
                    if (strlen($image->path) > 1) {
                        $fullpath = $images_dir . '/' . $image->path;
                        if (is_file($fullpath)) {
                            unlink($fullpath);
                        }
                        $image->delete();
                    }
                }
                $img->delete();
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => "error on cleaning up img " . $e->getMessage()];
        }
        return true;
    }

    public static function getRandomHash() {
        return md5('linuxbehave' . microtime());
    }

    /**
     * @param $where array e.g. ['entity_type' => 'actors_pair', 'entity_id' => $pairs_ids]
     * @return array [<entity_id>][<img_id>] => $img_row <-- webpath here
     */
    public static function getByCondition($where) {
        $raw_arr = Img::find()
            ->select('img.*, images.id as images_id, images.path as path, images.file_size as file_size')
            ->from('img')
            ->leftJoin('images', " images.img_id = img.id AND images.type = 'default' ")
            ->where($where)
            ->orderBy(['img.entity_id' => SORT_ASC, 'img.sort' => SORT_ASC])
            ->asArray()->all();
        $imgs = [];
        foreach($raw_arr as &$r) {
            if (!isset($imgs[$r['entity_id']])) {
                $imgs[$r['entity_id']] = [];
            }
            $r['webpath'] = '/images/' . $r['path'];
            $imgs[$r['entity_id']][] = $r;
        }
        return $imgs;
    }

}