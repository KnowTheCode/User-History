<section class="uh-filtering">
	<ul>
		<li class="no-list-style">
			<a class="button button__filterby<?php echo $this->get_button_class( 1 ); ?>" href="<?php echo home_url( 'your-activity-history?actid=1' ); ?>">
				<span class="fa fa-heart"></span> <?php _e( 'Favorites', 'user_history' ); ?>
			</a>
		</li>
		<li class="no-list-style">
			<a class="button button__filterby<?php echo $this->get_button_class( 3 ); ?>" href="<?php echo home_url( 'your-activity-history?actid=3' ); ?>">
				<span class="fa fa-check-circle"></span> <?php _e( 'Watched', 'user_history' ); ?>
			</a>
		</li>
		<li class="no-list-style">
			<a class="button button__filterby<?php echo $this->get_button_class( 4 ); ?>" href="<?php echo home_url( 'your-activity-history?actid=4' ); ?>">
				<span class="fa fa-clock-o"></span> <?php _e( 'Watch Later', 'user_history' ); ?>
			</a>
		</li>
	</ul>
</section>