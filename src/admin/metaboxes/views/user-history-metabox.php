<table class="form-table">
	<tbody>
		<tr valign="top">
			<th>Num</th>
			<th>
				<?php _e( 'Video ID', 'user_history' ); ?>
			</th>
			<th>
				<?php _e( 'Video Title', 'user_history' ); ?>
			</th>
		</tr>

	<?php for( $index = 0; $index < 30; $index++ ) : ?>

		<tr valign="top">
			<td width="5%"><?php echo $index + 1; ?></td>
			<td width="30%">
				<input type="hidden" name="user_history[_video_<?php echo $index; ?>][id]" value="<?php echo $this->get_value_from_record( $index, 'id' ); ?>" />
				<input type="hidden" name="user_history[_video_<?php echo $index; ?>][slug]" value="<?php echo $this->get_value_from_record( $index, 'slug' ); ?>" />
				<input class="large-text" type="text" name="user_history[_video_<?php echo $index; ?>][video_id]" id="video_id" value="<?php echo $this->get_value_from_record( $index, 'video_id' ); ; ?>" />
			</td>
			<td>
				<input class="large-text" type="text" name="user_history[_video_<?php echo $index; ?>][video_title]" id="video_title" value="<?php echo $this->get_value_from_record( $index, 'video_title' ); ; ?>" />
			</td>
		</tr>

	<?php endfor; ?>

	</tbody>
</table>