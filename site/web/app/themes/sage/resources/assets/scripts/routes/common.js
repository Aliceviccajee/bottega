import datepickerFactory from 'jquery-datepicker';
import debounce from 'lodash.debounce'

export default {
	init() {
		sessionStorage.SessionName = 'bottega-booking' ,

		datepickerFactory($);
		// JavaScript to be fired on all pages
		$(function () {
			$('#datepicker').datepicker({
				dateFormat: 'yy-m-d',
				minDate: 0,
				onSelect: function () {
					sessionStorage.setItem('date',this.value);
					$('#timepicker').html('<option>Checking availability...</option>')
					$.get('/index.php/wp-json/v1/booking/times', {
						date: this.value,
					}).done(function (data) {
						const html = data.data.map(function(time) {
							return `<option value="${time}">${time}</option>`;
						})
						$('#timepicker').html('<option value="">Select a timeslot</option>' + html);
					});
				},
			});
			$('#timepicker').on('change', function() {
				sessionStorage.setItem('slot',$(this).val());

				if ($(this).val() && $('#datepicker').val()) {
					$('.products').addClass('enabled');
				} else {
					$('.products').removeClass('enabled');
				}
			})
		});

		const bookingInfo = document.querySelector('.js-booking-info');

		if (bookingInfo) {
			const date = document.querySelector('.js-booking-info .date');
			const time = document.querySelector('.js-booking-info .time');
			date.textContent = date.textContent + ' ' + sessionStorage.getItem('date');
			time.textContent = time.textContent + ' ' + sessionStorage.getItem('slot');
		}

		const dateInput = $('#date_slot');
		const timeInput = $('#time_slot');
		if (dateInput) {
			dateInput.val(sessionStorage.getItem('date'));
		}
		if (timeInput) {
			timeInput.val(sessionStorage.getItem('slot'));
		}

		const postcodeInput = $('#postcode');

		function postcodeChecker () {
			const msg = $('.user-feedback');
			msg.text('Checking if we can deliver to you...')
			msg.removeClass('success fail')
			if (!$(this).val().replace(' ', '')) return;
			$.get('/index.php/wp-json/v1/booking/distance-check', {client_pc: $(this).val().replace(' ', '')}).done(function (data) {

				if (data.status == 'success') {
					msg.text(data.miles <= 2 ? 'Great news, we deliver to your area!' : 'Sorry, we aren\'t currently delivering to your area')
					msg.addClass(data.miles <= 2 ? 'success' : 'fail')
				} else if (data.status == 'invalid') {
					msg.text('Please enter a valid full postcode')
					msg.addClass('fail')
				} else {
					msg.text('Sorry, we couldn\'t find an address with that postcode')
					msg.addClass('fail')
				}

				$('#postcodeLabel').append(msg);

			});
		}
		postcodeInput.on('input', debounce(postcodeChecker, 500))

	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
