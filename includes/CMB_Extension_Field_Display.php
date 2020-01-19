<?php
/**
 * Class CMB_Extension_Field_Display
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Field_Display
{

    public function __construct($field_type)
    {
        add_filter('cmb2_pre_field_display_{$field_type}', array($this, 'get'), 10, 3);
    }

    /**
     * Get the corresponding display class for the field type.
     *
     * @param $pre_output
     * @param CMB2_Field $field Requested field type.
     * @param $display
     * @return CMB2_Field_Display
     * @since  1.0.0
     */
    public static function get($pre_output, CMB2_Field $field, $display)
    {

        switch ($field->type()) {
            case 'ajax_search':
                $type = new CMB2_Display_Ajax_Search($field);
                break;
            default:
                $type = $pre_output;
                break;
        }// End switch.

        return $type;
    }

}

class CMB2_Display_Ajax_Search extends CMB2_Field_Display
{
    /**
     * Display field.
     *
     * @since 1.0.0
     */
    protected function _display()
    {

        $object_to_search = !empty($this->field->args('search')) ? $this->field->args('search') : 'post';

        ob_start();
        $this->field->peform_param_callback('before_display_wrap');

        printf("<div class=\"cmb-column %s\" data-fieldtype=\"%s\">\n", $this->field->row_classes('display'), $this->field->type());

        $this->field->peform_param_callback('before_display');

        if (is_array($this->value)) : ?>
            <?php foreach ($this->value as $value) : ?>
                <a href="<?php echo cmb_ext_ajax()->object_link($this->field->args['id'], $value, $object_to_search); ?>"
                   class="edit-link">
                    <?php echo cmb_ext_ajax()->object_text($this->field->args['id'], $value, $object_to_search); ?>
                </a> <br>
            <?php endforeach; ?>
        <?php else : ?>
            <a href="<?php echo cmb_ext_ajax()->object_link($this->field->args['id'], $this->value, $object_to_search); ?>"
               class="edit-link">
                <?php echo cmb_ext_ajax()->object_text($this->field->args['id'], $this->value, $object_to_search); ?>
            </a>
        <?php endif;

        $this->field->peform_param_callback('after_display');

        echo "\n</div>";

        $this->field->peform_param_callback('after_display_wrap');

        return ob_get_clean();

    }
}