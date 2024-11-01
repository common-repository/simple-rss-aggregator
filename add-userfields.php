<?php

if( get_option('sra_user_field', '1') ):

function sra_add_custom_user_profile_fields( $user ) {
?>
    <h3><?php _e('Extra Profile Information', 'your_textdomain'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
                <label for="user_feed"><?php _e('Feed', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="user_feed" id="user_feed" value="<?php echo esc_attr( get_the_author_meta( 'user_feed', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your feed.', 'your_textdomain'); ?></span>
            </td>
        </tr>
        <!--tr>
            <th>
                <label for="user_twitter"><?php _e('Twitter', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="user_twitter" id="user_twitter" value="<?php echo esc_attr( get_the_author_meta( 'user_twitter', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your twitter.', 'your_textdomain'); ?></span>
            </td>
        </tr>
        <tr>
            <th>
                <label for="user_flickr"><?php _e('Flickr', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="user_flickr" id="user_flickr" value="<?php echo esc_attr( get_the_author_meta( 'user_flickr', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your flickr.', 'your_textdomain'); ?></span>
            </td>
        </tr-->
    </table>
<?php }
 
function sra_save_custom_user_profile_fields( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    
    update_usermeta( $user_id, 'user_feed', $_POST['user_feed'] );
    //update_usermeta( $user_id, 'user_twitter', $_POST['user_twitter'] );
    //update_usermeta( $user_id, 'user_flickr', $_POST['user_flickr'] );
}
 
add_action( 'show_user_profile', 'sra_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'sra_add_custom_user_profile_fields' );
 
add_action( 'personal_options_update', 'sra_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'sra_save_custom_user_profile_fields' );

endif;

?>
