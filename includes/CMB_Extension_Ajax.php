<?php
/**
 * Class CMB_Extension_Ajax
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Ajax extends CMB2_Ajax
{

    /**
     * Instance of this class.
     *
     * @since 1.0.0
     * @var object
     */
    protected static $instance;

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        // Ajax request
        add_action('wp_ajax_cmb_ajax_search_get_results', array($this, 'get_results'));

    }

    /**
     * Get the singleton instance of this class.
     *
     * @return CMB_Extension_Ajax
     * @since 1.0.0
     */
    public static function get_instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Ajax request : get results
     *
     * @since 1.0.0
     */
    public function get_results()
    {
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'cmb_ajax_search_get_results')) {
            // Wrong nonce
            die(json_encode(array(
                'error' => __('Error : Unauthorized action', 'cmb-ext')
            )));
        } else if ((!isset($_POST['field_id']) || empty($_POST['field_id']))
            || (!isset($_POST['object_type']) || empty($_POST['object_type']))) {
            // Wrong request parameters (field_id and object_type are mandatory)
            die(json_encode(array(
                'error' => __('Error : Wrong request parameters', 'cmb-ext')
            )));
        } else {
            $query_args = json_decode(stripslashes(htmlspecialchars_decode($_POST['query_args'])), true);
            $data = array();
            $results = array();

            switch ($_POST['object_type']) {
                case 'post':
                    $query_args['s'] = $_POST['query'];
                    $query = new WP_Query($query_args);
                    $results = $query->posts;
                    break;
                case 'user':
                    $query_args['search'] = '*' . $_POST['query'] . '*';
                    $query = new WP_User_Query($query_args);
                    $results = $query->results;
                    break;
                case 'term':
                    $query_args['search'] = $_POST['query'];
                    $query = new WP_Term_Query($query_args);
                    $results = $query->terms;
                    break;
            }

            foreach ($results as $result) :
                if ($_POST['object_type'] == 'term') {
                    $result_id = $result->term_id;
                } else {
                    $result_id = $result->ID;
                }

                $data[] = array(
                    'id' => $result_id,
                    'value' => $this->object_text($_POST['field_id'], $result_id, $_POST['object_type']),
                    'link' => $this->object_link($_POST['field_id'], $result_id, $_POST['object_type'])
                );
            endforeach;

            wp_send_json($data);
            exit;
        }
    }

    public function object_text($field_id, $object_id, $object_type)
    {
        $text = '';
        if ($object_type == 'post') {
            $text = get_the_title($object_id);
        } else if ($object_type == 'user') {
            $text = get_the_author_meta('display_name', $object_id);
        } else if ($object_type == 'term') {
            $term = get_term($object_id);
            $text = $term->name;
        }

        $text = apply_filters("cmb_ext_{$field_id}_ajax_search_result_text", $text, $object_id, $object_type);
        return $text;
    }

    public function object_link($field_id, $object_id, $object_type)
    {
        $link = '#';
        if ($object_type == 'post') {
            $link = get_edit_post_link($object_id);
        } else if ($object_type == 'user') {
            $link = get_edit_user_link($object_id);
        } else if ($object_type == 'term') {
            $link = get_edit_term_link($object_id);
        }

        $link = apply_filters("cmb_ext_{$field_id}_ajax_search_result_link", $link, $object_id, $object_type);
        return $link;
    }

}
