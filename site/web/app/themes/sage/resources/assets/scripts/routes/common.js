import datepickerFactory from 'jquery-datepicker';

export default {
	init() {
		datepickerFactory($);
		// JavaScript to be fired on all pages
		$(function () {
			$('#datepicker').datepicker({
				dateFormat: 'yy-m-d',
				minDate: 0,
				onSelect: function () {
					$.get('/index.php/wp-json/v1/booking/times', {
						date: this.value,
					}).done(function (data) {
						const html = data.data.map(function(time) {
							return `<option value="${time}">${time}</option>`;
						})
						$('#timepicker').html(html);
					});
				},
			});
		});
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
