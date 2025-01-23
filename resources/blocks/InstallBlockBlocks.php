<?php

namespace Studio1902\PeakCommands\Commands;

trait InstallBlockBlocks {

    public function getBlocks() {
        return
            [
                'call_to_action' => 'Call to action: Show a call to action. [alert-alarm-bell]',
                'cards' => 'Cards: Cards that link using the button fieldset. [link]',
                'collection' => 'Collection: Show collection entries. [file-content-list]',
                'columns' => 'Columns: Text columns with optional images and buttons. [layout-two-columns]',
                'divider' => 'Divider: A visual divider between blocks. [layout-table-row-insert]',
                'full_width_image' => 'Full width image: A full width image with optional text and button(s). [media-image-picture-orientation]',
                'index_content' => 'Index content: Render the currently mounted entries if available. [file-content-list]',
                'image_and_text' => 'Image and text: An image and text side by side. [text-formatting-image-left]',
                'images_grid' => 'Images grid: A multi row image grid. [layout-grid-dots]',
                'text_columns' => 'Text columns: Text wrapping in two columns. [layout-three-columns]',
            ]
        ;
    }
}
