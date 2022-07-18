<?php
function wpbc_contacts_page_handler()
{
    global $wpdb;

    $table = new Custom_Table_Example_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'wpbc'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Contacts', 'wpbc')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=contacts_form');?>"><?php _e('Add new', 'wpbc')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="contacts-table" method="POST">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}


function wpbc_contacts_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; 

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'name'      => '',
        'lastname'  => '',
        'email'     => '',
        'phone'     => null,
        'company'   => '',
        'web'       => '',  
        'two_email' => '',   
        'two_phone' => '',
        'job'       => '',        
        'address'   => '',
        'notes'     => '',
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);     

        $item_valid = wpbc_validate_contact($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'wpbc');
                } else {
                    $notice = __('There was an error while saving item', 'wpbc');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'wpbc');
                } else {
                    $notice = __('There was an error while updating item', 'wpbc');
                }
            }
        } else {
            
            $notice = $item_valid;
        }
    }
    else {
        
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'wpbc');
            }
        }
    }

    
    add_meta_box('contacts_form_meta_box', __('Contact data', 'wpbc'), 'wpbc_contacts_form_meta_box_handler', 'contact', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Contact', 'wpbc')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=contacts');?>"><?php _e('back to list', 'wpbc')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php do_meta_boxes('contact', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'wpbc')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function wpbc_contacts_form_meta_box_handler($item)
{
    ?>
<tbody >
		
	<div class="formdatabc">		
		
    <form >
		<div class="form2bc">
        <p>			
		    <label for="name"><?php _e('Name:', 'wpbc')?></label>
		<br>	
            <input id="name" name="name" type="text" value="<?php echo esc_attr($item['name'])?>"
                    required>
		</p><p>	
            <label for="lastname"><?php _e('Last Name:', 'wpbc')?></label>
		<br>
		    <input id="lastname" name="lastname" type="text" value="<?php echo esc_attr($item['lastname'])?>"
                    required>
        </p>
		</div>	
		<div class="form2bc">
			<p>
            <label for="email"><?php _e('E-Mail:', 'wpbc')?></label> 
		<br>	
            <input id="email" name="email" type="email" value="<?php echo esc_attr($item['email'])?>"
                   required>
        </p><p>	  
            <label for="phone"><?php _e('Phone:', 'wpbc')?></label> 
		<br>
			<input id="phone" name="phone" type="tel" value="<?php echo esc_attr($item['phone'])?>">
		</p>
		</div>
		<div class="form2bc">
			<p>
            <label for="company"><?php _e('Company:', 'wpbc')?></label> 
		<br>	
            <input id="company" name="company" type="text" value="<?php echo esc_attr($item['company'])?>">
        </p><p>	  
            <label for="web"><?php _e('Web:', 'wpbc')?></label> 
		<br>
			<input id="web" name="web" type="text" value="<?php echo esc_attr($item['web'])?>">
		</p>
		</div>	
		<div class="form3bc">
		<p>
            <label for="email"><?php _e('E-Mail:', 'wpbc')?></label> 
		<br>	
            <input id="email" name="two_email" type="email" value="<?php echo esc_attr($item['two_email'])?>">
        </p><p>	  
            <label for="phone"><?php _e('Phone:', 'wpbc')?></label> 
		<br>
			<input id="phone" name="two_phone" type="tel" value="<?php echo esc_attr($item['two_phone'])?>">
		</p><p>	  
            <label for="job"><?php _e('Job Title:', 'wpbc')?></label> 
		<br>
			<input id="job" name="job" type="text" value="<?php echo esc_attr($item['job'])?>">
		</p>		
		</div>	
		<div>		
			<p>
		    <label for="address"><?php _e('Address:', 'wpbc')?></label> 
		<br>
            <textarea id="addressbc" name="address" cols="100" rows="3" maxlength="240"><?php echo esc_attr($item['address'])?></textarea>
		</p><p>  
            <label for="notes"><?php _e('Notes:', 'wpbc')?></label>
		<br>
            <textarea id="notesbc" name="notes" cols="100" rows="3" maxlength="240"><?php echo esc_attr($item['notes'])?></textarea>
		</p>
		</div>	
		</form>
		</div>
</tbody>
<?php
}
