<?php

class acf_field_foundation_row extends acf_field
{

  private static $breakpoints = [
    'small' => ['name' => 'Mobile', 'icon' => 'dashicons-smartphone'],
    'medium' => ['name' => 'Tablet', 'icon' => 'dashicons-tablet'],
    'large' => ['name' => 'Desktop', 'icon' => 'dashicons-desktop'],
  ];

  function __construct()
  {
    $this->name = 'foundation_row';
    $this->label = __('Foundation Row Settings', 'acf-foundation-column');
    $this->category = 'layout';
    $this->defaults = array();
    parent::__construct();
  }

  /**
   *  render_field()
   *
   *  Create the HTML interface for your field
   */
  function render_field( $field )
  {
    $dir = plugin_dir_url( __FILE__ );
    foreach (self::$breakpoints as $breakpoint => $info) {
      if (!isset($field['value']['breakpoints'][$breakpoint])) {
        $field['value']['breakpoints'][$breakpoint] = '';
      }
    }
    $field_value = $field['value'];
    ?>
    <div class="container acf-container-foundation-row">
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <td></td>
            <td>Columns</td>
          </tr>

          <?php foreach (self::$breakpoints as $breakpoint => $info) : ?>
            <tr valign="top">
              <td scope="row">
                <label>
                  <span class="dashicons <?php echo $info['icon']; ?>"></span> <?php echo $info['name']; ?>
                </label>
              </td>
              <td>
                <select
                  class="widefat"
                  name="<?php echo $field['name']; ?>[breakpoints][<?php echo $breakpoint; ?>]"
                >
                  <option value=""><?php echo __('- inherit -', 'foundation-column'); ?></option>
                  <?php foreach (range(1, 12) as $columns) : ?>
                    <option
                      value="<?php echo $breakpoint; ?>-up-<?php echo $columns; ?>"
                      <?php echo $field_value['breakpoints'][$breakpoint] == "$breakpoint-up-$columns" ? 'selected="selected"' : ''; ?>
                    >
                      <?php echo $columns; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php
  }

  /**
   *  update_field()
   *
   *  This filter is applied to the $field before it is saved to the database
   */
  function update_value( $value, $post_id, $field )
  {
    $value['classes'] = [];
    foreach ($value['breakpoints'] as $breakpoint => $class) {
      $value['classes'][] = $class;
    }
    $value['classes'] = array_values(array_filter($value['classes']));
    return $value;
  }

  /**
   *  input_admin_enqueue_scripts()
   *
   *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
   *  Use this action to add CSS + JavaScript to assist your render_field() action.
   */
  function input_admin_enqueue_scripts()
  {
    $dir = plugin_dir_url( __FILE__ );
    wp_register_style( 'acf-input-foundation-column', "{$dir}css/input.css" );
    wp_enqueue_style( 'acf-input-foundation-column' );
  }
}

new acf_field_foundation_row();
