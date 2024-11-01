	
	<div class="wrap">

		<div style="float:right;width:400px">
			<div style="float:right; margin-top:10px">
				 <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode('http://wordpress.org/extend/plugins/simple-rss-aggregator/') ?>&amp;layout=box_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21"
					scrolling="no" frameborder="0" style="overflow:hidden; width:90px; height:61px; margin:0 0 0 10px; float:right" allowTransparency="true"></iframe>
					<strong style="line-height:25px;"><?php echo __('Do you like Advanced Settings Plugin? '); ?></strong>
			</div>
		</div>

		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Simple RSS Agregator</h2>
		
		<form action="options.php" method="post">
			
			<?php settings_fields( 'simple-rss-aggregator-options' ); ?>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Home</th>
					<td>
						<label for="sra_show_posts_at_home">
							<input name="sra_show_posts_at_home" type="checkbox" id="sra_show_posts_at_home" value="1" <?php if(get_option('sra_show_posts_at_home')) echo 'checked="checked"' ?> />
							Show posts at home
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Synchronize dates</th>
					<td>
						<label for="sra_sync">
							<input name="sra_sync" type="checkbox" id="sra_sync" value="1" <?php if( get_option('sra_sync') ) echo 'checked="checked"' ?> />
							Synchronize blog post date with the feed post date
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">User field</th>
					<td>
						<label for="sra_user_field">
							<input name="sra_user_field" type="checkbox" id="sra_user_field" value="1" <?php if( get_option('sra_user_field', '1') ) echo 'checked="checked"' ?> />
							Add a feed user field
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Context</th>
					<td>
						<label for="sra_filter_content">
							<input name="sra_filter_content" type="checkbox" id="sra_filter_content" value="1" <?php if( get_option('sra_filter_content', '1') ) echo 'checked="checked"' ?> />
							Filter content in single page and add "read more" link button
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Thumbnails</th>
					<td>
						<label for="sra_thumbnail">
							<input name="sra_thumbnail" type="checkbox" id="sra_thumbnail" value="1" <?php if( get_option('sra_thumbnail') ) echo 'checked="checked"' ?> />
							Get the first image from text and set the post thumbnail
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Terms</th>
					<td>
						<label for="sra_terms">
							<input name="sra_terms" type="checkbox" id="sra_terms" value="1" <?php if( get_option('sra_terms') ) echo 'checked="checked"' ?> />
							Update terms too
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Link</th>
					<td>
						<label title="0">
							<input name="sra_link" type="radio" id="sra_link" value="0" <?php if(!get_option('sra_link')) echo 'checked="checked"' ?> />
							Open default single page</label> <br />
						
						<label title="1">
							<input name="sra_link" type="radio" id="sra_link" value="1" <?php if(get_option('sra_link')==1) echo 'checked="checked"' ?> />
							Open external feed link</label> <br />
						
						<label title="2">
							<input name="sra_link" type="radio" id="sra_link" value="2" <?php if(get_option('sra_link')==2) echo 'checked="checked"' ?> />
							Open default single page and redirect to feed link (better for counters)</label> <br />
						
						<!--label title="2">
							<input name="sra_link" type="radio" id="sra_link" value="3" <?php if(get_option('sra_link')==3) echo 'checked="checked"' ?> />
							Open in another window with frames (frame.html tamplate)</label> <br /-->
						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sra_time_to_update">Time to update</label></th>
					<td><input name="sra_time_to_update" type="text" size="2" maxlength="3" id="sra_time_to_update" value="<?php echo (int)get_option('sra_time_to_update', '2'); ?>" /> (hours)</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sra_items">Items</label></th>
					<td>
						<input name="sra_items" type="text" size="2" maxlength="3" id="items" value="<?php echo (int)get_option('sra_items', '3'); ?>" />
						Number of items updated for each feed checking
					</td>
				</tr>
			</table>
			
			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save changes') ?>"></p>
		</form>
	</div>
