<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImageUpload extends Model{

    public $image;

    public function rules() {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions'=>'jpg,png']
        ];
    }

    public function uploadFile($file,$currentImage) {
        $this->image = $file;
        if ($this->validate()){
            $this->deleteCurrentImage($currentImage);
            return $this->saveImage();
        }
    }

    public function getFolder(){
        return Yii::getAlias('@web').'uploads/';
    }

    private function generateFilename(){
        return strtolower(md5(uniqid($this->image->baseName))) . '.' . $this->image->getExtension();
    }

    public function deleteCurrentImage($currentImage) {
        if ($this->fileExists($currentImage) && !empty($currentImage) && $currentImage!= null)
            unlink($this->getFolder().$currentImage);
    }

    public function fileExists($currentImage) {
        return file_exists($this->getFolder().$currentImage);
    }

    public function saveImage(){
        $filename = $this->generateFilename();
        $this->image->saveAs($this->getFolder().$filename);
        return $filename;
    }
}