<?php
function wpsc_details_page_handler()
{
    global $wpdb;

    $table = new Custom_contact_details_with_wp_List();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
    <div class="wrap">

        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('Details') ?> <a class="add-new-h2"
                                      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=details_form'); ?>"><?php _e('Add new') ?></a>
        </h2>
        <?php echo $message; ?>

        <form id="details-table" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php $table->display() ?>
        </form>

    </div>
    <?php
}


function wpsc_details_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wsc';

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'name' => '',
        'email' => '',
        'address'=> '',
        'phone' => 0,
        'date' => ''

    );


    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {

        $item = shortcode_atts($default, $_REQUEST);

        $item_valid = wpsc_validate_detail($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved');
                } else {
                    $notice = __('There was an error while saving item');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated');
                } else {
                    $notice = __('There was an error while updating item');
                }
            }
        } else {

            $notice = $item_valid;
        }
    } else {

        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found');
            }
        }
    }


    add_meta_box('details_form_meta_box', __('Detail data'), 'wpsc_details_form_meta_box_handler', 'detail', 'normal', 'default');

    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('Detail') ?> <a class="add-new-h2"
                                     href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=details'); ?>"><?php _e('back to list') ?></a>
        </h2>

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error"><p><?php echo $notice ?></p></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div id="message" class="updated"><p><?php echo $message ?></p></div>
        <?php endif; ?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>"/>

            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

            <div class="metabox-holder">
                <div id="post-body">
                    <div id="post-body-content">

                        <?php do_meta_boxes('detail', 'normal', $item); ?>
                        <input type="submit" value="<?php _e('Save') ?>" id="submit" class="button-primary"
                               name="submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function wpsc_details_form_meta_box_handler($item)
{   
    ?>
    <tbody>

    <div>

        <form>
            <div>
                <p>
                    <label for="name"><?php _e('Name:') ?></label>
                    <br>
                    <input id="name" name="name" type="text" value="<?php echo esc_attr($item['name']) ?>"
                           required>
                </p>
            </div>
            <div>
                <p>
                    <label for="email"><?php _e('E-Mail:') ?></label>
                    <br>
                    <input id="email" name="email" type="email" value="<?php echo esc_attr($item['email']) ?>"
                           required>
                </p>
            </div>
            <div>
                <p>
                    <label for="address"><?php _e('Address:') ?></label>
                    <br>
                    <textarea id="address" name="address" 
                           required><?php echo esc_attr($item['address']) ?></textarea>
                </p>
            </div>
            <div>
                <p>
                    <label for="phone"><?php _e('Phone:') ?></label>
                    <br>
                    <input id="phone" name="phone" type="text" value="<?php echo esc_attr($item['phone']) ?>"
                           required>
                </p>
            </div>
            <div>
                <p>
                    <label for="date"><?php _e('Date:') ?></label>
                    <br>
                    <input id="date" name="date" type="date" value="<?php echo esc_attr($item['date']) ?>"
                           required>
                </p>
            </div>
        </form>
    </div>
    </tbody>
    <?php
}
