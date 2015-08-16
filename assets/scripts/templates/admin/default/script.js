var j = jQuery.noConflict();
var opts;
var spinner;

(function($){
	j(document).on("ready", function(){
    // Ocultar y mostrar paneles
    j("body").on('click', '.oc-panel', function(){
      var panel = j(this).parent().parent().next();
      if (j(this).hasClass('glyphicon-chevron-up')) {
        panel.fadeOut();
        j(this).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
      } else {
        panel.fadeIn();
        j(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
      }
    });

    j('#toggle-menu').on('click', function(ev){
      ev.preventDefault();
      pageslide = j('#pageslide-left');
      if (pageslide.css('display') == 'none') {
        j('#pageslide-left').css('display', 'block');
        j('.main-container .main-content .container').removeClass('width-auto');
        j('.main-container').animate({'margin-left': '90px'});
      } else {
        j('#pageslide-left').css('display', 'none');
        j('.main-container .main-content .container').addClass('width-auto');
        j('.main-container').animate({'margin-left': '0'});
      }
    });

    /**************** Scrollbar Menu **************************/
    var heightHeader = j('header').outerHeight();
    var heightNavegador = j(window).height();

    j('#pageslide-left').height(heightNavegador - heightHeader);

    j("#pageslide-left").mCustomScrollbar({
      axis:"y",
      setHeight: heightNavegador - heightHeader,
      autoHideScrollbar: true,
    });

    opts = {
      lines: 13, // The number of lines to draw
      length: 20, // The length of each line
      width: 10, // The line thickness
      radius: 30, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: '#000', // #rgb or #rrggbb or array of colors
      speed: 1, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: '50%', // Top position relative to parent
      left: '50%' // Left position relative to parent
    };

    target = document.getElementById('spin');
    spinner = new Spinner(opts);

    j(":file").filestyle();

    j('.down-menu').on('click', function() {
      if (j(this).hasClass('fa-angle-down')) {
        j(this).removeClass('fa-angle-down').addClass('fa-angle-up');
        j(this).parent().css('padding-bottom', '0');
      } else {
        j(this).removeClass('fa-angle-up').addClass('fa-angle-down');
        j(this).parent().css('padding-bottom', '14px');
      }

      if (j(this).next().hasClass('hidden')) {
        j(this).next().removeClass('hidden').addClass('show');
      } else {
        j(this).next().removeClass('show').addClass('hidden');
      }
    })
	});
})(jQuery);

function date(format, timestamp) {
  	// example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
  	// returns 1: '09:09:40 m is month'
  	// example 2: date('F j, Y, g:i a', 1062462400);
  	// returns 2: 'September 2, 2003, 2:26 am'
  	// example 3: date('Y W o', 1062462400);
  	// returns 3: '2003 36 2003'
  	// example 4: x = date('Y m d', (new Date()).getTime()/1000);
  	// example 4: (x+'').length == 10 // 2009 01 09
  	// returns 4: true
  	// example 5: date('W', 1104534000);
  	// returns 5: '53'
  	// example 6: date('B t', 1104534000);
  	// returns 6: '999 31'
  	// example 7: date('W U', 1293750000.82); // 2010-12-31
  	// returns 7: '52 1293750000'
  	// example 8: date('W', 1293836400); // 2011-01-01
  	// returns 8: '52'
  	// example 9: date('W Y-m-d', 1293974054); // 2011-01-02
  	// returns 9: '52 2011-01-02'

  	var that = this;
  	var jsdate, f;
  	// Keep this here (works, but for code commented-out below for file size reasons)
  	// var tal= [];
  	var txt_words = [
    	'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab',
    	'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    	'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  	];
  	// trailing backslash -> (dropped)
  	// a backslash followed by any character (including backslash) -> the character
  	// empty string -> empty string
  	var formatChr = /\\?(.?)/gi;
  	var formatChrCb = function (t, s) {
    	return f[t] ? f[t]() : s;
  	};
  	var _pad = function (n, c) {
    	n = String(n);
    	while (n.length < c) {
      		n = '0' + n;
    	}
    	return n;
  	};
  	f = {
    	// Day
    	d: function () {
      		// Day of month w/leading 0; 01..31
      		return _pad(f.j(), 2);
    	},
    	D: function () {
      		// Shorthand day name; Mon...Sun
      		return f.l()
        	.slice(0, 3);
    	},
    	j: function () {
      		// Day of month; 1..31
      		return jsdate.getDate();
    	},
    	l: function () {
      		// Full day name; Monday...Sunday
      		return txt_words[f.w()] + 'day';
    	},
    	N: function () {
      		// ISO-8601 day of week; 1[Mon]..7[Sun]
      		return f.w() || 7;
    	},
    	S: function () {
      		// Ordinal suffix for day of month; st, nd, rd, th
      		var j = f.j();
      		var i = j % 10;
      		if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
        		i = 0;
      		}
      		return ['st', 'nd', 'rd'][i - 1] || 'th';
    	},
    	w: function () {
      		// Day of week; 0[Sun]..6[Sat]
      		return jsdate.getDay();
    	},
    	z: function () {
      		// Day of year; 0..365
      		var a = new Date(f.Y(), f.n() - 1, f.j());
      		var b = new Date(f.Y(), 0, 1);
      		return Math.round((a - b) / 864e5);
    	},

    	// Week
    	W: function () {
      		// ISO-8601 week number
      		var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      		var b = new Date(a.getFullYear(), 0, 4);
      		return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    	},

    	// Month
    	F: function () {
      		// Full month name; January...December
      		return txt_words[6 + f.n()];
    	},
    	m: function () {
      		// Month w/leading 0; 01...12
      		return _pad(f.n(), 2);
    	},
    	M: function () {
      		// Shorthand month name; Jan...Dec
      		return f.F()
        	.slice(0, 3);
    	},
    	n: function () {
      		// Month; 1...12
      		return jsdate.getMonth() + 1;
    	},
    	t: function () {
      		// Days in month; 28...31
      		return (new Date(f.Y(), f.n(), 0))
        	.getDate();
    	},

    	// Year
    	L: function () {
      		// Is leap year?; 0 or 1
      		var j = f.Y();
      		return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
    	},
    	o: function () {
      		// ISO-8601 year
      		var n = f.n();
      		var W = f.W();
      		var Y = f.Y();
      		return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    	},
    	Y: function () {
      		// Full year; e.g. 1980...2010
      		return jsdate.getFullYear();
    	},
    	y: function () {
    		// Last two digits of year; 00...99
      		return f.Y()
        	.toString()
        	.slice(-2);
    	},

    	// Time
    	a: function () {
      		// am or pm
      		return jsdate.getHours() > 11 ? 'pm' : 'am';
    	},
    	A: function () {
      		// AM or PM
      		return f.a()
        	.toUpperCase();
    	},
    	B: function () {
      		// Swatch Internet time; 000..999
      		var H = jsdate.getUTCHours() * 36e2;
      		// Hours
      		var i = jsdate.getUTCMinutes() * 60;
      		// Minutes
      		// Seconds
      		var s = jsdate.getUTCSeconds();
      		return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    	},
    	g: function () {
      		// 12-Hours; 1..12
      		return f.G() % 12 || 12;
    	},
    	G: function () {
      		// 24-Hours; 0..23
      		return jsdate.getHours();
    	},
    	h: function () {
      		// 12-Hours w/leading 0; 01..12
      		return _pad(f.g(), 2);
    	},
    	H: function () {
      		// 24-Hours w/leading 0; 00..23
      		return _pad(f.G(), 2);
    	},
    	i: function () {
      		// Minutes w/leading 0; 00..59
      		return _pad(jsdate.getMinutes(), 2);
    	},
    	s: function () {
      		// Seconds w/leading 0; 00..59
      		return _pad(jsdate.getSeconds(), 2);
    	},
    	u: function () {
      		// Microseconds; 000000-999000
      		return _pad(jsdate.getMilliseconds() * 1000, 6);
    	},

    	// Timezone
    	e: function () {
      		// Timezone identifier; e.g. Atlantic/Azores, ...
      		// The following works, but requires inclusion of the very large
      		// timezone_abbreviations_list() function.
      		/* return that.date_default_timezone_get();*/
      		throw 'Not supported (see source code of date() for timezone on how to add support)';
    	},
    	I: function () {
      		// DST observed?; 0 or 1
      		// Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      		// If they are not equal, then DST is observed.
      		var a = new Date(f.Y(), 0);
      		// Jan 1
      		var c = Date.UTC(f.Y(), 0);
      		// Jan 1 UTC
      		var b = new Date(f.Y(), 6);
      		// Jul 1
      		// Jul 1 UTC
      		var d = Date.UTC(f.Y(), 6);
      		return ((a - c) !== (b - d)) ? 1 : 0;
    	},
    	O: function () {
      		// Difference to GMT in hour format; e.g. +0200
      		var tzo = jsdate.getTimezoneOffset();
      		var a = Math.abs(tzo);
      		return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    	},
    	P: function () {
      		// Difference to GMT w/colon; e.g. +02:00
      		var O = f.O();
      		return (O.substr(0, 3) + ':' + O.substr(3, 2));
    	},
    	T: function () {
      		// Timezone abbreviation; e.g. EST, MDT, ...
      		// The following works, but requires inclusion of the very
      		// large timezone_abbreviations_list() function.
      		/* var abbr, i, os, _default;
			if (!tal.length) {
				tal = that.timezone_abbreviations_list();
			}
			if (that.php_js && that.php_js.default_timezone) {
				_default = that.php_js.default_timezone;
				for (abbr in tal) {
					for (i = 0; i < tal[abbr].length; i++) {
						if (tal[abbr][i].timezone_id === _default) {
							return abbr.toUpperCase();
						}
					}
				}
			}
			for (abbr in tal) {
				for (i = 0; i < tal[abbr].length; i++) {
					os = -jsdate.getTimezoneOffset() * 60;
					if (tal[abbr][i].offset === os) {
						return abbr.toUpperCase();
					}
				}
 			}*/
      		return 'UTC';
    	},
    	Z: function () {
      		// Timezone offset in seconds (-43200...50400)
      		return -jsdate.getTimezoneOffset() * 60;
    	},

    	// Full Date/Time
    	c: function () {
      		// ISO-8601 date.
      		return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    	},
    	r: function () {
      		// RFC 2822
      		return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    	},
    	U: function () {
      		// Seconds since UNIX epoch
      		return jsdate / 1000 | 0;
   	 	}
  	};
  	this.date = function (format, timestamp) {
    	that = this;
    	jsdate = (timestamp === undefined ? new Date() : // Not provided
    		(timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
      		new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    	);
    	return format.replace(formatChr, formatChrCb);
  	};
  	return this.date(format, timestamp);
}

// Validar email
function validEmail(email) {
  if (/(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\w*)(\.{1})(\w{2,3})/.test(email)){
    return true;
  } else {
    return false;
  }
}