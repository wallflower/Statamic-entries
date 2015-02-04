<?php

class Fieldtype_entries extends Fieldtype {

    public function render() {
        $folders = array_get($this->field_config, 'folder');
        $conditions = array_get($this->field_config, 'conditions');
        $sort_dir = array_get($this->field_config, 'sort_dir');

        $field_type = array_get($this->field_config, 'field-type', 'suggest');
        $field_key = array_get($this->field_config, 'field-key', 'slug');
        $field_value = array_get($this->field_config, 'field-value', 'title');

        // set the fieldtype config options
        $config = array_get($this->field_config, 'field-options', array());

        $filters = array(
            'type' => 'entries',
            'conditions' => $conditions
        );

        if ($field_type == 'suggest' && $sort_dir != null) {
            // add sort to suggest options
            $config['sort_dir'] = $sort_dir;
        }

        $content_set = ContentService::getContentByFolders($folders);
        $content_set->filter($filters);

        if ($field_type != 'suggest' && $sort_dir != null) {
            // conduct sort on content set
            $content_set->sort($field_value, $sort_dir);
        }

        $entries = $content_set->get();

        // loop through each entry and set key values
        foreach ($entries as $key => $entry) {
            $config['options'][$entry[$field_key]] = $entry[$field_value];
        }

        // use standard renderer for desired fieldtype
        $html = Fieldtype::render_fieldtype($field_type, $this->field, $config, $this->field_data, $this->tabindex);

        // need to set css class of containing div to be that of the desired fieldtype
        $div_name = "item_$this->field";
        $html .= $this->js->inline("
            var $div_name = document.querySelector('[name*=\"\[$this->field\]\"]').parentNode.parentNode
            if ($div_name != null) {
                $div_name.className = $div_name.className.replace(\"input-entries\", \"input-$field_type\");
            }
        ");

        return $html;
    }

}
