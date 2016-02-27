<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	$( document ).on('mousedown', '.time', function(event) {
		
		<?php
			
			$this->load->language('time');
			
		?>
		
		$(this).timepicker({
			
			showButtonPanel: true,
			timeFormat: 'HH:mm:ss',
			
			closeText: '<?= lang('action_ok'); ?>',
			timeOnlyTitle: '<?= lang('time_choose_time'); ?>',
			currentText: '<?= lang('time_now'); ?>',
			timeText: '<?= lang('time_time'); ?>',
			hourText: '<?= lang('time_hour'); ?>',
			minuteText: '<?= lang('time_minute'); ?>',
			secondText: '<?= lang('time_second'); ?>',
			millisecText: '<?= lang('time_millisec'); ?>',
			microsecText: '<?= lang('time_microsec'); ?>',
			timezoneText: '<?= lang('time_timezone'); ?>'
			
		});
		
	});
	
</script>
