
import datepickerFactory from 'jquery-datepicker';

export default {
  init() {
    datepickerFactory($);
    // JavaScript to be fired on all pages
    $( function() {
      $( '#datepicker' ).datepicker();
    } );
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
