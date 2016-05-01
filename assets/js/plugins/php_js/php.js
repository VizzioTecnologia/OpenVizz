
// http://phpjs.org/

function rawurlencode (str) {
	// http://kevin.vanzonneveld.net
	// +	 original by: Brett Zamir (http://brett-zamir.me)
	// +			input by: travc
	// +			input by: Brett Zamir (http://brett-zamir.me)
	// +	 bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +			input by: Michael Grier
	// +	 bugfixed by: Brett Zamir (http://brett-zamir.me)
	// +			input by: Ratheous
	// +			reimplemented by: Brett Zamir (http://brett-zamir.me)
	// +	 bugfixed by: Joris
	// +			reimplemented by: Brett Zamir (http://brett-zamir.me)
	// %					note 1: This reflects PHP 5.3/6.0+ behavior
	// %				note 2: Please be aware that this function expects to encode into UTF-8 encoded strings, as found on
	// %				note 2: pages served as UTF-8
	// *		 example 1: rawurlencode('Kevin van Zonneveld!');
	// *		 returns 1: 'Kevin%20van%20Zonneveld%21'
	// *		 example 2: rawurlencode('http://kevin.vanzonneveld.net/');
	// *		 returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
	// *		 example 3: rawurlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
	// *		 returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
	str = (str + '').toString();

	// Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
	// PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
	return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
	replace(/\)/g, '%29').replace(/\*/g, '%2A');
}

function rawurldecode (str) {
	// http://kevin.vanzonneveld.net
	// +	 original by: Brett Zamir (http://brett-zamir.me)
	// +			input by: travc
	// +			input by: Brett Zamir (http://brett-zamir.me)
	// +	 bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +			input by: Ratheous
	// +	 reimplemented by: Brett Zamir (http://brett-zamir.me)
	// +			input by: lovio
	// +	 improved by: Brett Zamir (http://brett-zamir.me)
	// %				note 1: Please be aware that this function expects to decode from UTF-8 encoded strings, as found on
	// %				note 1: pages served as UTF-8
	// *		 example 1: rawurldecode('Kevin+van+Zonneveld%21');
	// *		 returns 1: 'Kevin+van+Zonneveld!'
	// *		 example 2: rawurldecode('http%3A%2F%2Fkevin.vanzonneveld.net%2F');
	// *		 returns 2: 'http://kevin.vanzonneveld.net/'
	// *		 example 3: rawurldecode('http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a');
	// *		 returns 3: 'http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a'
	// *		 example 4: rawurldecode('-22%97bc%2Fbc');
	// *		 returns 4: '-22—bc/bc'
	// *		 example 4: urldecode('%E5%A5%BD%3_4');
	// *		 returns 4: '\u597d%3_4'
	return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function () {
			// PHP tolerates poorly formed escape sequences
			return '%25';
	}));
}

function trim (str, charlist) {
	// http://kevin.vanzonneveld.net
	// +	 original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +	 improved by: mdsjack (http://www.mdsjack.bo.it)
	// +	 improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
	// +			input by: Erkekjetter
	// +	 improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +			input by: DxGx
	// +	 improved by: Steven Levithan (http://blog.stevenlevithan.com)
	// +		tweaked by: Jack
	// +	 bugfixed by: Onno Marsman
	// *		 example 1: trim('		Kevin van Zonneveld		');
	// *		 returns 1: 'Kevin van Zonneveld'
	// *		 example 2: trim('Hello World', 'Hdle');
	// *		 returns 2: 'o Wor'
	// *		 example 3: trim(16, 1);
	// *		 returns 3: 6
	var whitespace, l = 0,
		i = 0;
	str += '';

	if (!charlist) {
		// default list
		whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	} else {
		// preg_quote custom list
		charlist += '';
		whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
	}

	l = str.length;
	for (i = 0; i < l; i++) {
		if (whitespace.indexOf(str.charAt(i)) === -1) {
			str = str.substring(i);
			break;
		}
	}

	l = str.length;
	for (i = l - 1; i >= 0; i--) {
		if (whitespace.indexOf(str.charAt(i)) === -1) {
			str = str.substring(0, i + 1);
			break;
		}
	}

	return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}