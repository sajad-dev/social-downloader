<?php

namespace App\Jobs;

use App\Models\Quality;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class DownloadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $link;

    public $type;


    public $quality;

    public $quality_list = ['144p' => 17, '360p' => 18, '720p' => 22];

    public $address_exe_file = 'D:\Programing\Projects\youtube\public\yt-dlp.exe';

    public function __construct($link, $type, $quality)
    {
        $this->link = $link;

        $this->type = $type;

        $this->quality = $quality;
    }

    public function handle()
    {
        //check status
        if ($this->type == 'youtube') {

            //get Information
            exec($this->address_exe_file . ' -j ' . $this->link, $name_output);
            $obj = json_decode($name_output[0]);

            //save Information Playlist In database
            $obj_list = [];
            if ($obj->_type === 'playlist') {
                $obj_list = $obj->entries;
                foreach ($obj_list as $obj) {
                    $time = $obj->duration_string;
                    $name = $obj->title;
                    $description = $obj->description;
                    $channel = $obj->channel_id;
                    $id = $obj->id;
                    $channel_name = $obj->channel;
                    $webpage_url = $obj->webpage_url;
                    $channel_url = $obj->channel_url;
                    Video::factory()->create(
                        [
                            'name' => $name,
                            'caption' => $description,
                            'channel_name' => $channel_name,
                            'channel_id' => $channel,
                            'video_id' => $id,
                            'video_url' => $webpage_url,
                            'channel_url' => $channel_url,
                            'time' => $time
                        ]
                    );

                }
            } // Save Solo Video In Database
            else {
                $time = $obj->duration_string;
                $name = $obj->title;
                $description = $obj->description;
                $channel = $obj->channel_id;
                $id = $obj->id;
                $channel_name = $obj->channel;
                $channel_url = $obj->channel_url;
                $webpage_url = $obj->webpage_url;
                Video::create(
                    [
                        'name' => $name,
                        'caption' => $description,
                        'channel_name' => $channel_name,
                        'channel_id' => $channel,
                        'video_id' => $id,
                        'video_url' => $webpage_url,
                        'channel_url' => $channel_url,
                        'time' => $time
                    ]
                );

            }

            //get Video Saved
            if (count($obj_list) != 0) {
                $video_get = Video::query()->orderByDesc('id')->limit(count($obj_list))->get();

            } else {

                $video_get = Video::query()->orderByDesc('id')->limit(1)->get();
            }

            //download Video
            foreach ($this->quality as $x) {
                $quality = '-f ' . Arr::get($this->quality_list, $x);
                $address_video = storage_path('\app\Video\\') . '%(id)s\%(title)s -' . $x . '.mp4';
                exec($this->address_exe_file . ' -o "' . $address_video . '" ' . $quality . ' ' . $this->link, $output, $re);

                //save Database
                foreach ($video_get as $id) {
                    Quality::create([
                        'quality' => $x,
                        'link_download' => $address_video,
                        'videos_id' => $id->id
                    ]);
                }
            }
        } else {
            //For other Status
            return Storage::disk('local')->put(now() . '/' . now() . '.mp4', file_get_contents($this->link));
        }
    }
}
