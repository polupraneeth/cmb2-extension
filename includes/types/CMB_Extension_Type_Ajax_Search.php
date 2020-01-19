<?php
/**
 * Class CMB_Extension_Type_Ajax_Search
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: Post Search Ajax (https://github.com/rubengc/cmb2-field-ajax-search/)
// Special thanks to Magina (http://magina.fr/) and Rubengc for his awesome work

class CMB_Extension_Type_Ajax_Search extends CMB2_Type_Text
{

    /**
     * Default search option
     *
     * @var string
     */
    public $object_to_search = 'post';

    /**
     * The optional value for the Ajax Search field
     *
     * @var string
     */
    public $value = '';

    /**
     * Constructor
     *
     * @param CMB2_Types $types
     * @param array $args
     * @since 1.0.0
     *
     */
    public function __construct(CMB2_Types $types, $args = array())
    {
        parent::__construct($types, $args);
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
    }

    public function render($args = array())
    {
        $default_limit = 1;
        $field_name = $this->_name();
        $query_args = (array)$this->field->args('query_args');

        //find the search object
        $this->object_to_search = !empty($this->field->args('search')) ? $this->field->args('search') : 'post';

        if ($this->field->args('multiple') == true) {
            $default_limit = -1; // 0 or -1 means unlimited
            $list_result = $this->multi_list_result($field_name);
            $input_value = '';

        } else {
            if (is_array($this->value)) {
                $this->value = $this->value[0];
            }

            $list_result = parent::render(array(
                'type' => 'hidden',
                'name' => $field_name,
                'value' => $this->value,
                'desc' => false
            ));

            $input_value = ($this->value ? cmb_ext_ajax()->object_text($field_name, $this->value, $this->object_to_search) : '');
        }

        $input = parent::render(array(
            'type' => 'text',
            'name' => $field_name . '_input',
            'id' => $field_name . '_input',
            'class' => 'cmb-ext-ajax-search cmb-' . $this->object_to_search . '-ajax-search',
            'value' => $input_value,
            'desc' => false,
            'js_dependencies' => array('jquery-autocomplete-ajax-search'),
            'data-multiple' => $this->field->args('multiple') ? $this->field->args('multiple') : '0',
            'data-limit' => $this->field->args('limit') ? $this->field->args('limit') : $default_limit,
            'data-sortable' => $this->field->args('sortable') ? $this->field->args('sortable') : '0',
            'data-object-type' => $this->object_to_search,
            'data-query-args' => $query_args ? htmlspecialchars(json_encode($query_args), ENT_QUOTES, 'UTF-8') : ''
        ));

        $spinner = array(
            'class' => 'cmb-ext-ajax-search-spinner',
            'src' => admin_url('images/spinner.gif')
        );

        return $this->rendered(
            sprintf('%s %s <img %s /> %s',$list_result, $input, $this->concat_attrs($spinner), $this->_desc(true))
        );

    }

    function multi_list_result($field_name)
    {
        $li = '';

        if (isset($this->value) && !empty($this->value)) {
            if (!is_array($this->value)) {
                $this->value = array($this->value);
            }
            $sortable = !empty($this->field->args('sortable')) ? '<span class="hndl"></span>' : '';
            foreach ($this->value as $val) :

                $input = parent::render(array(
                    'type' => 'hidden',
                    'name' => $field_name . '[]',
                    'value' => $val,
                    'target' => '_blank',
                    'class' => 'edit-link',
                    'desc' => false
                ));

                $a_arg = array(
                    'href' => cmb_ext_ajax()->object_link($field_name, $val, $this->object_to_search),
                    'target' => '_blank',
                    'class' => 'edit-link',
                    'label' => cmb_ext_ajax()->object_text($field_name, $val, $this->object_to_search)
                );
                $a_remove_arg = array(
                    'class' => 'remover',
                    'label' => '<span class="dashicons dashicons-no"></span><span class="dashicons dashicons-dismiss"></span>'
                );

                $li .= $this->li($sortable, $input, $a_arg, $a_remove_arg);
            endforeach;
        }

        $ul_arg = array(
            'id' => $field_name . '_results',
            'class' => 'cmb-ext-ajax-search-results cmb-' . $this->object_to_search,
            'li' => $li
        );

        return $this->ul($ul_arg);
    }

    protected function ul($a)
    {
        return sprintf('<ul %s>%s</ul>', $this->concat_attrs($a, array('li')), $a['li']);
    }

    protected function li($sortable, $input, $a_arg, $a_remove_arg)
    {
        return sprintf(
            '<li >%s %s %s %s </li>', $sortable, $input, $this->a($a_arg), $this->a($a_remove_arg)
        );
    }

    protected function a($a)
    {
        return sprintf('<a %s>%s</a>', $this->concat_attrs($a, array('label')), $a['label']);
    }

}
