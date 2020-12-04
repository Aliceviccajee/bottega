import datepickerFactory from 'jquery-datepicker';
import debounce from 'lodash.debounce'

export default {
	init() {
		sessionStorage.SessionName = 'bottega-booking',

		datepickerFactory($);

		let isPostCodeValid = false;

		function postcodeValidator() {
			if ($('#timepicker').val() && $('#datepicker').val() && isPostCodeValid) {
				$('.products').addClass('enabled');
			} else {
				$('.products').removeClass('enabled');
			}
		}

		// JavaScript to be fired on all pages
		$('#datepicker').datepicker({
			dateFormat: 'yy-m-d',
			minDate: 0,
			beforeShowDay: function(date) {
				const day = date.getDay();
				const curDate = $.datepicker.formatDate('yy-mm-dd', date);

				return [(day != 0 && day != 1 && day != 2 && day != 3 && curDate !== '2020-11-26')];
			},
			onSelect: function () {
				sessionStorage.setItem('date',this.value);
				$('#timepicker').html('<option>Checking availability...</option>')
				$.get('/index.php/wp-json/v1/booking/times', {
					date: this.value,
				}).done(function (data) {
					const html = Object.keys(data.data).map(function(label) {
						return `<option value="${data.data[label]}">${label}</option>`;
					})
					$('#timepicker').html('<option value="">Select a timeslot</option>' + html);
					postcodeValidator();

				});
			},
		});
		$('#timepicker').on('change', function() {
			sessionStorage.setItem('slot',$(this).val());
			postcodeValidator();
		})

		const bookingInfo = $('.js-booking-info')	;

		if (bookingInfo.length && !bookingInfo.hasClass('has-info')) {
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
					msg.text(data.miles <= +window.LOCALISED_VARS.deliveryRadius ? 'We deliver to your area!' : 'Sorry, we aren\'t currently delivering to your area')
					msg.addClass(data.miles <= +window.LOCALISED_VARS.deliveryRadius ? 'success' : 'fail')
					isPostCodeValid = data.miles <= +window.LOCALISED_VARS.deliveryRadius;
				} else if (data.status == 'invalid') {
					msg.text('Please enter a valid full postcode')
					msg.addClass('fail')
					isPostCodeValid = false;
				} else {
					msg.text('Sorry, we couldn\'t find an address with that postcode')
					msg.addClass('fail')
					isPostCodeValid = false;
				}

				postcodeValidator();

				$('#postcodeLabel').append(msg);

			});
		}
		postcodeInput.on('input', debounce(postcodeChecker, 500))

	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
