<tr>
	<td class="uhlist__status"><span class="fa fa-check-circle"></span></td>
	<td class="uhlist__video"><a href="<?php echo $url ?>"><?php esc_html_e( stripslashes( $record->video_title ) ); ?></a></td>
	<td class="uhlist__document"><a href="<?php echo $permalink ?>"><?php echo get_the_title( $post_id ); ?></a></td>
</tr>
