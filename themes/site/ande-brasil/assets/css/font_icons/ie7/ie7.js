/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referring to this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'vui\'">' + entity + '</span>' + html;
	}
	var icons = {
		'vui-icon-ok': '&#xe600;',
		'vui-icon-apply': '&#xe600;',
		'vui-icon-add': '&#xe601;',
		'vui-icon-increase': '&#xe601;',
		'vui-icon-decrease': '&#xe606;',
		'vui-icon-cancel': '&#xe602;',
		'vui-icon-delete': '&#xe602;',
		'vui-icon-remove': '&#xe602;',
		'vui-icon-find': '&#xe603;',
		'vui-icon-search': '&#xe603;',
		'vui-icon-login': '&#xe604;',
		'vui-icon-security': '&#xe604;',
		'vui-icon-logout': '&#xe605;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/vui-icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
