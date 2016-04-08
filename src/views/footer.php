<footer class="embed-video__footer">
	<ul class="embed-video__activity-list">
		<li>
			<a <?php echo $this->attributes['fav']; ?> data-inactive-text="<?php echo $this->config->text['fav'][0]; ?>" data-active-text="<?php echo $this->config->text['fav'][1]; ?>">
				<span class="fa fa-heart"></span> <span class="activity-text"><?php echo $this->text['fav']; ?></span>
			</a>
		</li>
		<li>
			<a <?php echo $this->attributes['watched']; ?> data-inactive-text="<?php echo $this->config->text['watched'][0]; ?>" data-active-text="<?php echo $this->config->text['watched'][1]; ?>">
				<span class="fa fa-check-circle"></span> <span class="activity-text"><?php echo $this->text['watched']; ?></span>
			</a>
		</li>
		<li>
			<a <?php echo $this->attributes['later']; ?> data-inactive-text="<?php echo $this->config->text['later'][0]; ?>" data-active-text="<?php echo $this->config->text['later'][1]; ?>">
				<span class="fa fa-clock-o"></span> <span class="activity-text"><?php echo $this->text['later']; ?></span>
			</a>
		</li>
	</ul>
</footer>