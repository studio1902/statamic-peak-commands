<?php

namespace App\Fieldtypes;

use Statamic\Fieldtypes\Select;
use Statamic\Facades\GlobalSet;
use Illuminate\Support\Str;

class DefaultBlockSelect extends Select
{
    protected $component = 'select';
    protected $categories = ['special'];
    
    protected function configFieldItems(): array
    {
        return [
            'placeholder' => [
                'display' => 'Placeholder',
                'instructions' => 'The placeholder text when nothing is selected.',
                'type' => 'text',
            ],
        ];
    }

    public function preload(): array
    {
        $options = [];
        
        // Get the Default blocks global data
        $globalSet = GlobalSet::findByHandle('default_blocks');
        
        if ($globalSet && $globalSet->inCurrentSite()) {
            $data = $globalSet->inCurrentSite()->data();
            $defaultBlocks = $data->get('default_blocks', []);
            
            foreach ($defaultBlocks as $block) {
                // Only include groups that are available as blocks
                if ($block['available_as_block'] ?? false) {
                    $label = $block['label'] ?? 'Unnamed Block';
                    $slug = Str::slug($label);
                    $options[] = [
                        'value' => $slug,
                        'label' => $label
                    ];
                }
            }
        }
        
        return array_merge(parent::preload(), [
            'options' => $options
        ]);
    }

    public function preProcess($data)
    {
        return $data;
    }

    public function process($data)
    {
        return $data;
    }
}