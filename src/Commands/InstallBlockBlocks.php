<?php

namespace Studio1902\PeakCommands\Commands;

trait InstallBlockBlocks {

    public function getBlocks() {
        return
            [
                'call_to_action' => 'Call to action: Show a call to action.',
                'cards' => 'Cards: Cards that link using the button fieldset.',
                'collection' => 'Collection: Show collection entries.',
                'columns' => 'Columns: Text columns with optional images and buttons.',
                'divider' => 'Divider: A visual divider between blocks.',
                'full_width_image' => 'Full width image: A full width image with optional text and button(s).',
                'index_content' => 'Index content: Render the currently mounted entries if available.',
                'image_and_text' => 'Image and text: An image and text side by side.',
                'images_grid' => 'Images grid: A multi row image grid.',
                'text_columns' => 'Text columns: Text wrapping in two columns.',
            ]
        ;
    }
}
