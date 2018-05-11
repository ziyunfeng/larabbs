<?php

namespace App\Handlers;

use Intervention\Image\Image;

class ImageUploadHandler {

    protected $allowed_ext = [
        'png', 'jpg', 'gif', 'jpeg'
    ];

    public function save($file, $folder, $file_prefix, $max_width = false) {

        //  文件夹储存路径规则
        $folder_name = "uploads/images/$folder/" . date ("Ym/d", time ());

        //  文件具体路径
        $upload_path    =   public_path () . '/' . $folder_name;

        //文件后缀
        $extension = strtolower ($file->getClientOriginalExtension()) ?: "png";

        //  判断后缀是否在范围内
        if(!in_array ($extension, $this->allowed_ext)) return false;

        //  构建文件名
        $filename = $file_prefix . "_" . time () . "_" . str_random (10) . "." . $extension;

        $file->move($upload_path, $filename);

        //  如果限制了图片宽度，则对图片进行裁剪
        if($max_width && $extension != 'gif') {
            $this->reduceSize ($upload_path . '/' .$filename, $max_width);
        }

        return [
            'path'  =>  config ('app.url') . "/$folder_name/$filename"
        ];

    }


    public function save2($file, $folder, $file_prefix, $max_width = false) {

        //  构建储存的文件夹规则
        $folder_name = "uploads/images/$folder/" . date ('Ym/d', time ());

        //  文件具体存储的物理路径
        $upload_path = public_path () . '/' . $folder_name;

        //  获取文件后缀
        $extension = strtolower ($file->getClientOriginalExtension()) ?: 'png';

        //  拼接文件名
        $filename = $file_prefix . '_' . time () . '_' . str_random (10) . '.' . $extension;

        if(!in_array ($extension, $this->allowed_ext)) return false;

        $file->move($upload_path, $filename);

        if($max_width && $extension != 'gif') {

            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }


        return [
            'path'  =>  config ('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width) {
        $image = \Intervention\Image\Facades\Image::make($file_path);

        $image->resize ($max_width, null, function ($constraint){
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        $image->save ();

    }

}