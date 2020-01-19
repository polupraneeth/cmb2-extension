<?php
/**
 * Class CMB_Extension_Type_Animation
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: Animation (https://github.com/rubengc/cmb2-field-animation)
// Special thanks to Rubengc for his awesome work

class CMB_Extension_Type_Animation extends CMB2_Type_Multi_Base
{

    /**
     * The optional value for the Animation field
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

    /**
     * Render field
     */
    public function render($args = array())
    {
        // Setup selected groups, by default, all
        $selected_groups = array(
            'seekers',

            'entrances', // All entrances
            'bouncing_entrances',
            'fading_entrances',
            'rotating_entrances',
            'sliding_entrances',
            'zoom_entrances',

            'exits', // All exits
            'bouncing_exits',
            'fading_exits',
            'rotating_exits',
            'sliding_exits',
            'zoom_exits',

            'flippers',
            'lightspeed',
            'specials',
        );

        if (is_array($this->field->args('groups'))) {
            $selected_groups = $this->field->args('groups');
        }

        // Setup custom animations
        $custom_animations = array();

        if (is_array($this->field->args('custom_animations'))) {
            $custom_animations = $this->field->args('custom_animations');
        }

        // Setup custom groups
        $custom_groups = array();

        if (is_array($this->field->args('custom_groups'))) {
            $custom_groups = $this->field->args('custom_groups');
        }

        $options = array();

        if (in_array('seekers', $selected_groups)) {
            $options['Attention Seekers'] = array(
                'bounce' => 'Bounce',
                'flash' => 'Flash',
                'pulse' => 'Pulse',
                'rubberBand' => 'Rubber Band',
                'shake' => 'Shake',
                'swing' => 'Swing',
                'tada' => 'Tada',
                'wobble' => 'Wobble',
                'jello' => 'Jello',
            );

            // Append custom animations
            if (isset($custom_animations['seekers'])) {
                $options['Attention Seekers'] += $custom_animations['seekers'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('bouncing_entrances', $selected_groups)) {
            $options['Bouncing Entrances'] = array(
                'bounceIn' => 'Bounce In',
                'bounceInDown' => 'Bounce In Down',
                'bounceInLeft' => 'Bounce In Left',
                'bounceInRight' => 'Bounce In Right',
                'bounceInUp' => 'Bounce In Up',
            );

            // Append custom animations
            if (isset($custom_animations['bouncing_entrances'])) {
                $options['Bouncing Entrances'] += $custom_animations['bouncing_entrances'];
            }
        }

        if (in_array('exits', $selected_groups) || in_array('bouncing_exits', $selected_groups)) {
            $options['Bouncing Exits'] = array(
                'bounceOut' => 'Bounce Out',
                'bounceOutDown' => 'Bounce Out Down',
                'bounceOutLeft' => 'Bounce Out Left',
                'bounceOutRight' => 'Bounce Out Right',
                'bounceOutUp' => 'Bounce Out Up',
            );

            // Append custom animations
            if (isset($custom_animations['bouncing_exits'])) {
                $options['Bouncing Exits'] += $custom_animations['bouncing_exits'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('fading_entrances', $selected_groups)) {
            $options['Fading Entrances'] = array(
                'fadeIn' => 'Fade In',
                'fadeInDown' => 'Fade In Down',
                'fadeInDownBig' => 'Fade In Down Big',
                'fadeInLeft' => 'Fade In Left',
                'fadeInLeftBig' => 'Fade In Left Big',
                'fadeInRight' => 'Fade In Right',
                'fadeInRightBig' => 'Fade In Right Big',
                'fadeInUp' => 'Fade In Up',
                'fadeInUpBig' => 'Fade In Up Big',
            );

            // Append custom animations
            if (isset($custom_animations['fading_entrances'])) {
                $options['Fading Entrances'] += $custom_animations['fading_entrances'];
            }
        }

        if (in_array('exits', $selected_groups) || in_array('fading_exits', $selected_groups)) {
            $options['Fading Exits'] = array(
                'fadeOut' => 'Fade Out',
                'fadeOutDown' => 'Fade Out Down',
                'fadeOutDownBig' => 'Fade Out Down Big',
                'fadeOutLeft' => 'Fade Out Left',
                'fadeOutLeftBig' => 'Fade Out Left Big',
                'fadeOutRight' => 'Fade Out Right',
                'fadeOutRightBig' => 'Fade Out Right Big',
                'fadeOutUp' => 'Fade Out Up',
                'fadeOutUpBig' => 'Fade Out Up Big',
            );

            // Append custom animations
            if (isset($custom_animations['fading_exits'])) {
                $options['Fading Exists'] += $custom_animations['fading_exits'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('exits', $selected_groups) || in_array('flippers', $selected_groups)) {
            $options['Flippers'] = array(
                'flip' => 'Flip',
                'flipInX' => 'Flip In X',
                'flipInY' => 'Flip In Y',
                'flipOutX' => 'Flip Out X',
                'flipOutY' => 'Flip Out Y',
            );

            if (!in_array('flippers', $selected_groups)) {

                unset($options['Flippers']['flip']);

                if (!in_array('entrances', $selected_groups)) {
                    // Remove flip entrances
                    unset($options['Flippers']['flipInX']);
                    unset($options['Flippers']['flipInY']);
                } else if (!in_array('exits', $selected_groups)) {
                    // Remove flip exists
                    unset($options['Flippers']['flipOutX']);
                    unset($options['Flippers']['flipOutY']);
                }
            }

            // Append custom animations
            if (isset($custom_animations['flippers'])) {
                $options['Flippers'] += $custom_animations['flippers'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('exits', $selected_groups) || in_array('lightspeed', $selected_groups)) {
            $options['Light Speed'] = array(
                'lightSpeedIn' => 'Light Speed In',
                'lightSpeedOut' => 'Light Speed Out',
            );

            if (!in_array('lightspeed', $selected_groups)) {
                if (!in_array('entrances', $selected_groups)) {
                    // Remove light speed entrances
                    unset($options['Light Speed']['lightSpeedIn']);
                } else if (!in_array('exits', $selected_groups)) {
                    // Remove light speed exists
                    unset($options['Light Speed']['lightSpeedOut']);
                }
            }

            // Append custom animations
            if (isset($custom_animations['lightspeed'])) {
                $options['Light Speed'] += $custom_animations['lightspeed'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('rotating_entrances', $selected_groups)) {
            $options['Rotating Entrances'] = array(
                'rotateIn' => 'Rotate In',
                'rotateInDownLeft' => 'Rotate In Down Left',
                'rotateInDownRight' => 'Rotate In Down Right',
                'rotateInUpLeft' => 'Rotate In Up Left',
                'rotateInUpRight' => 'Rotate In Up Right',
            );

            // Append custom animations
            if (isset($custom_animations['rotating_entrances'])) {
                $options['Rotating Entrances'] += $custom_animations['rotating_entrances'];
            }
        }

        if (in_array('exits', $selected_groups) || in_array('rotating_exits', $selected_groups)) {
            $options['Rotating Exits'] = array(
                'rotateOut' => 'Rotate Out',
                'rotateOutDownLeft' => 'Rotate Out Down Left',
                'rotateOutDownRight' => 'Rotate Out Down Right',
                'rotateOutUpLeft' => 'Rotate Out Up Left',
                'rotateOutUpRight' => 'Rotate Out Up Right',
            );

            // Append custom animations
            if (isset($custom_animations['rotating_exits'])) {
                $options['Rotating Exits'] += $custom_animations['rotating_exits'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('sliding_entrances', $selected_groups)) {
            $options['Sliding Entrances'] = array(
                'slideInUp' => 'Slide In Up',
                'slideInDown' => 'Slide In Down',
                'slideInLeft' => 'Slide In Left',
                'slideInRight' => 'Slide In Right',

            );

            // Append custom animations
            if (isset($custom_animations['sliding_entrances'])) {
                $options['Sliding Entrances'] += $custom_animations['sliding_entrances'];
            }
        }

        if (in_array('exits', $selected_groups) || in_array('sliding_exits', $selected_groups)) {
            $options['Sliding Exits'] = array(
                'slideOutUp' => 'Slide Out Up',
                'slideOutDown' => 'Slide Out Down',
                'slideOutLeft' => 'Slide Out Left',
                'slideOutRight' => 'Slide Out Right',
            );

            // Append custom animations
            if (isset($custom_animations['sliding_exits'])) {
                $options['Sliding Exits'] += $custom_animations['sliding_exits'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('zoom_entrances', $selected_groups)) {
            $options['Zoom Entrances'] = array(
                'zoomIn' => 'Zoom In',
                'zoomInDown' => 'Zoom In Down',
                'zoomInLeft' => 'Zoom In Left',
                'zoomInRight' => 'Zoom In Right',
                'zoomInUp' => 'Zoom In Up',
            );

            // Append custom animations
            if (isset($custom_animations['zoom_entrances'])) {
                $options['Zoom Entrances'] += $custom_animations['zoom_entrances'];
            }
        }

        if (in_array('exits', $selected_groups) || in_array('zoom_exits', $selected_groups)) {
            $options['Zoom Exits'] = array(
                'zoomOut' => 'Zoom Out',
                'zoomOutDown' => 'Zoom Out Down',
                'zoomOutLeft' => 'Zoom Out Left',
                'zoomOutRight' => 'Zoom Out Right',
                'zoomOutUp' => 'Zoom Out Up',
            );

            // Append custom animations
            if (isset($custom_animations['zoom_exits'])) {
                $options['Zoom Exits'] += $custom_animations['zoom_exits'];
            }
        }

        if (in_array('entrances', $selected_groups) || in_array('exits', $selected_groups) || in_array('specials', $selected_groups)) {
            $options['Specials'] = array(
                'hinge' => 'Hinge',
                'jackInTheBox' => 'Jack In The Box',
                'rollIn' => 'Roll In',
                'rollOut' => 'Roll Out',
            );

            if (!in_array('specials', $selected_groups)) {
                if (!in_array('entrances', $selected_groups)) {
                    // Remove special entrances
                    unset($options['Specials']['jackInTheBox']);
                    unset($options['Specials']['rollIn']);
                } else if (!in_array('exits', $selected_groups)) {
                    // Remove special exists
                    unset($options['Specials']['hinge']);
                    unset($options['Specials']['rollOut']);
                }
            }

            // Append custom animations
            if (isset($custom_animations['specials'])) {
                $options['Specials'] += $custom_animations['specials'];
            }
        }

        // Add custom groups (just if there are custom animations on custom groups)
        foreach ($custom_groups as $custom_group => $custom_group_label) {
            if (in_array($custom_group, $selected_groups) && isset($custom_animations[$custom_group])) {
                $options[$custom_group_label] = $custom_animations[$custom_group];
            }
        }

        $options_string = '';

        $options_string .= $this->select_option(array(
            'label' => __('Select an animation', 'cmb-ext'),
            'value' => '',
            'checked' => !$this->value
        ));

        foreach ($options as $group_label => $group) {

            $options_string .= '<optgroup label="' . $group_label . '">';

            foreach ($group as $key => $label) {
                $options_string .= $this->select_option(array(
                    'label' => $label,
                    'value' => $key,
                    'checked' => $this->value == $key
                ));
            }

            $options_string .= '</optgroup>';

        }

        $select = $this->types->select(array(
            'name' => $this->_name(),
            'class' => 'cmb-ext-animation-select',
            'desc' => '',
            'id' => $this->_id(),
            'options' => $options_string,
        ));

        if ($this->field->args('preview')) {
            $preview = '<span class="cmb-ext-animation-preview-button button"><span class="dashicons dashicons-controls-play"></span></span>';
            $preview .= '<span class="cmb-ext-animation-preview-text">' . __('Preview', 'cmb-ext') . '</span>';
        } else {
            $preview = '';
        }

        return $this->rendered(
            sprintf("<div class='cmb-ext-animation'>%s %s %s</div>", $select, $preview, $this->_desc(true))
        );

    }


}
