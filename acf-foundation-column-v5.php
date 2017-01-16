<?php

class acf_field_foundation_column extends acf_field
{

  private static $breakpoints = [
    'small' => ['name' => 'Mobile', 'icon' => 'dashicons-smartphone'],
    'medium' => ['name' => 'Tablet', 'icon' => 'dashicons-tablet'],
    'large' => ['name' => 'Desktop', 'icon' => 'dashicons-desktop'],
  ];

  function __construct()
  {
    $this->name = 'foundation_column';
    $this->label = __('Foundation Column Settings', 'acf-foundation-column');
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
    if (!isset($field['value']['shrink'])) {
      $field['value']['shrink'] = '';
    }
    if (!isset($field['value']['alignment'])) {
      $field['value']['alignment'] = '';
    }
    foreach (self::$breakpoints as $breakpoint => $info) {
      $options = ['columns' => '', 'expand' => '', 'offset' => '', 'order' => ''];
      if (isset($field['value']['breakpoints'][$breakpoint])) {
        $options = wp_parse_args($field['value']['breakpoints'][$breakpoint], $options);
      }
      $field['value']['breakpoints'][$breakpoint] = $options;
    }
    $field_value = $field['value'];
    ?>
    <div class="container acf-container-foundation-column">
      <p>
        <span><?php _e('Shrink', 'foundation-column');?></span>&nbsp;
        <input
          type="checkbox"
          name="<?php echo $field['name'];?>[shrink]"
          value="shrink"
          <?php echo $field_value['shrink'] == 'shrink' ? 'checked="checked"' : ''; ?>
        />
      </p>
      <p class="acf-foundation-column-alignment">
        <span><?php _e('Alignment', 'foundation-column');?></span>&nbsp;
        <select
          name="<?php echo $field['name']; ?>[alignment]"
        >
          <option></option>
          <?php foreach (['top', 'bottom', 'middle', 'stretch'] as $align) : ?>
            <option
              value="align-self-<?php echo $align; ?>"
              <?php echo $field_value['alignment'] == "align-self-$align" ? 'selected="selected"' : ''; ?>
            >
              <?php echo ucfirst($align); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </p>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <td scope="row"><span><?php _e('Grid', 'foundation-column');?></span></td>
            <td>Column</td>
            <td>Offset</td>
            <td>Order</td>
            <td>Expand</td>
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
                  name="<?php echo $field['name']; ?>[breakpoints][<?php echo $breakpoint; ?>][columns]"
                >
                  <option value=""><?php echo __('- inherit -', 'foundation-column'); ?></option>
                  <?php foreach (range(1, 12) as $columns) : ?>
                    <option
                      value="<?php echo $breakpoint; ?>-<?php echo $columns; ?>"
                      <?php echo $field_value['breakpoints'][$breakpoint]['columns'] == "$breakpoint-$columns" ? 'selected="selected"' : ''; ?>
                    >
                      <?php echo $columns; ?>/12
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <select
                  class="widefat"
                  name="<?php echo $field['name']; ?>[breakpoints][<?php echo $breakpoint; ?>][offset]"
                >
                  <option></option>
                  <?php foreach (range(1, 12) as $offset) : ?>
                    <option
                      value="<?php echo $breakpoint; ?>-offset-<?php echo $offset; ?>"
                      <?php echo $field_value['breakpoints'][$breakpoint]['offset'] == "$breakpoint-offset-$offset" ? 'selected="selected"' : ''; ?>
                    >
                      <?php echo $offset; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <select
                  class="widefat"
                  name="<?php echo $field['name']; ?>[breakpoints][<?php echo $breakpoint; ?>][order]"
                >
                  <option></option>
                  <?php foreach (range(1, 6) as $order) : ?>
                    <option
                      value="<?php echo $breakpoint; ?>-order-<?php echo $order; ?>"
                      <?php echo $field_value['breakpoints'][$breakpoint]['order'] == "$breakpoint-order-$order" ? 'selected="selected"' : ''; ?>
                    >
                      <?php echo $order; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <input
                  type="checkbox"
                  name="<?php echo $field['name'];?>[breakpoints][<?php echo $breakpoint; ?>][expand]"
                  value="<?php echo $breakpoint; ?>-expand"
                  <?php echo $field_value['breakpoints'][$breakpoint]['expand'] == "$breakpoint-expand" ? 'checked="checked"' : ''; ?>
                />
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
    if (!isset($value['classes'])) {
      $value['classes'] = [];
    }
    if (!empty($value['shrink'])) {
      $value['classes'][] = $value['shrink'];
    }
    if (!empty($value['alignment'])) {
      $value['classes'][] = $value['alignment'];
    }
    foreach ($value['breakpoints'] as $breakpoint => $options) {
      $value['classes'] = array_merge($value['classes'], array_values($options));
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

new acf_field_foundation_column();
