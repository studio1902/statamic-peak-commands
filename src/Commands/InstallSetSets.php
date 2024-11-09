<?php

namespace Studio1902\PeakCommands\Commands;

trait InstallSetSets {

    public function getSets() {
        return
            [
                'read_more' => 'Read more: Link to a related article. [content-book-open]',
                'video_self_hosted' => 'Video self hosted: Add a self hosted video. [media-webcam-video]',
            ]
        ;
    }
}
