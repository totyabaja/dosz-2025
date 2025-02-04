<?php

namespace TotyaDev\TotyaDevMediaManager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class FolderResource extends JsonResource
{
    public function toArray($request)
    {
        $media = Media::query()
            ->where('collection_name', $this->collection);


        $subFolders = Folder::query()
            ->where('model_id', $this->id)
            ->where('model_type', Folder::class);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'media' => config('totyadev-media-manager.api.resources.media')::collection($media->get()),
            'folders' => config('totyadev-media-manager.api.resources.folders')::collection($subFolders->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
