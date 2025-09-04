<?php

namespace App\Fieldtypes;

use Statamic\Fieldtypes\Select;
use Statamic\Facades\GlobalSet;
use Illuminate\Support\Str;

class BlockGroupSelect extends Select
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
        
        // Get the Block Groups global data
        $globalSet = GlobalSet::findByHandle('block_groups');
        
        if ($globalSet && $globalSet->inCurrentSite()) {
            $data = $globalSet->inCurrentSite()->data();
            $blockGroups = $data->get('block_groups', []);
            
            foreach ($blockGroups as $group) {
                // Only include groups that are available as blocks
                if ($group['available_as_block'] ?? false) {
                    $label = $group['label'] ?? 'Unnamed Block';
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