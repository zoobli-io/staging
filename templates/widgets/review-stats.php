<div class="ts-review-bars">
	
	<div class="ts-percentage-bar excellent">
		<div class="ts-bar-data">
			<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_review_excellent_icon'), [ 'aria-hidden' => 'true' ] ); ?>
			<p>Excellent</p>
			<span><?= absint( $pct['excellent'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['excellent'] ) ?>%;"></div>
		</div>
	</div>
	<div class="ts-percentage-bar very-good">
		<div class="ts-bar-data">
			<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_review_verygood_icon'), [ 'aria-hidden' => 'true' ] ); ?>
			<p>Very good</p>
			<span><?= absint( $pct['very_good'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['very_good'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar good">
		<div class="ts-bar-data">
			<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_review_good_icon'), [ 'aria-hidden' => 'true' ] ); ?>
			<p>Good</p>
			<span><?= absint( $pct['good'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['good'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar fair">
		<div class="ts-bar-data">
			<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_review_fair_icon'), [ 'aria-hidden' => 'true' ] ); ?>
			<p>Fair</p>
			<span><?= absint( $pct['fair'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['fair'] ) ?>%;"></div>
		</div>
	</div>

	<div class="ts-percentage-bar poor">
		<div class="ts-bar-data">
			<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_review_poor_icon'), [ 'aria-hidden' => 'true' ] ); ?>
			<p>Poor</p>
			<span><?= absint( $pct['poor'] ) ?>%</span>
		</div>
		<div class="ts-bar-chart">
			<div style="width: <?= absint( $pct['poor'] ) ?>%;"></div>
		</div>
	</div>
</div>

