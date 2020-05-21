<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class PlatformFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $type = 0;
       if (substr($this->url,strripos($this->url,".")+1) == 'mp4'){
            $type = 1;
       }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'thumb' => $this->thumb,
            'extension' => $this->extension,
            'size' => $this->size,
            'width' => $this->width,
            'color' => $this->color,
            'status' => $this->status,
            'sort' => $this->sort,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'title' => $this->title,
            'describe' => $this->describe,
            'front_cover' => $this->front_cover,
            'type' => $type,
        ];
    }
}
