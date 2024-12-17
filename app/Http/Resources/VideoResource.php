<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class VideoResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $values = $this->collection;
        $arr = [];
        $it = [];
        foreach (Arr::get($values, 'video') as $value) {
            $idParent = Arr::get($value, 'id');
            $it = Arr::prepend($it, [], 'video');
            $it['video'] = Arr::prepend($it['video'], $value, 'video_data');
            $it['video'] = Arr::prepend($it['video'], [], 'quality');
            foreach (Arr::get($values, 'quality') as $item) {
                $id = Arr::get($item, 'videos_id');
                if ($id == $idParent) $it['video']['quality'] = Arr::prepend($it['video']['quality'], $item);
            }
            array_push($arr, $it);
        }
        return [
            'data' => $arr,
        ];
    }
}
