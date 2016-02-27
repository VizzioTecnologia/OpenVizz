<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| USER AGENT TYPES
| -------------------------------------------------------------------
| This file contains four arrays of user agent data.  It is used by the
| User Agent Class to help identify browser, platform, robot, and
| mobile device data.  The array keys are used to identify the device
| and the array values are used to set the actual name of the item.
|
*/

$platforms = array (
					'windows nt 6.0'	=> 'Windows Longhorn',
					'windows nt 5.2'	=> 'Windows 2003',
					'windows nt 5.0'	=> 'Windows 2000',
					'windows nt 5.1'	=> 'Windows XP',
					'windows nt 4.0'	=> 'Windows NT 4.0',
					'winnt4.0'			=> 'Windows NT 4.0',
					'winnt 4.0'			=> 'Windows NT',
					'winnt'				=> 'Windows NT',
					'windows 98'		=> 'Windows 98',
					'win98'				=> 'Windows 98',
					'windows 95'		=> 'Windows 95',
					'win95'				=> 'Windows 95',
					'windows phone 8.0'	=> 'Windows Phone 8.0',
					'windows'			=> 'Unknown Windows OS',
					'rim tablet os'		=> 'QNX',
					'bb10'				=> 'QNX',
					'os x'				=> 'Mac OS X',
					'ppc mac'			=> 'Power PC Mac',
					'freebsd'			=> 'FreeBSD',
					'ppc'				=> 'Macintosh',
					
					'kfapwi'			=> "Android/Linux",
					'kfapwa'			=> "Android/Linux",
					'kfapwi'			=> "Android/Linux",
					'kfapwa'			=> "Android/Linux",
					'kfthwi'			=> "Android/Linux",
					'kfthwa'			=> "Android/Linux",
					'kfsowi'			=> "Android/Linux",
					'kfjwi'				=> "Android/Linux",
					'kfjwa'				=> "Android/Linux",
					'silk/1.'			=> "Android/Linux",
					'kfot'				=> "Android/Linux",
					'kftt'				=> "Android/Linux",
					'kftbwi'			=> "Android/Linux",
					'kfmewi'			=> "Android/Linux",
					'kffowi'			=> "Android/Linux",
					'kfsawi'			=> "Android/Linux",
					'kfsawa'			=> "Android/Linux",
					'kfaswi'			=> "Android/Linux",
					'kfarwi'			=> "Android/Linux",
					'sd4930ur'			=> "Android/Linux",
					'android'			=> "Android/Linux",
					
					'meego'				=> "MeeGo/Linux",
					
					'linux'				=> 'Linux',
					'debian'			=> 'Debian',
					'sunos'				=> 'Sun Solaris',
					'beos'				=> 'BeOS',
					'apachebench'		=> 'ApacheBench',
					'aix'				=> 'AIX',
					'irix'				=> 'Irix',
					'osf'				=> 'DEC OSF',
					'hp-ux'				=> 'HP-UX',
					'netbsd'			=> 'NetBSD',
					'bsdi'				=> 'BSDi',
					'openbsd'			=> 'OpenBSD',
					'gnu'				=> 'GNU/Linux',
					'unix'				=> 'Unknown Unix OS',
				);


// The order of this array should NOT be changed. Many browsers return
// multiple browser types so we want to identify the sub-type first.
$browsers = array(
					'PlayBook'			=> 'PlayBook',
					'Flock'				=> 'Flock',
					'Chrome'			=> 'Chrome',
					'Opera'				=> 'Opera',
					'MSIE'				=> 'Internet Explorer',
					'Internet Explorer'	=> 'Internet Explorer',
					'Shiira'			=> 'Shiira',
					'Firefox'			=> 'Firefox',
					'Chimera'			=> 'Chimera',
					'Phoenix'			=> 'Phoenix',
					'Firebird'			=> 'Firebird',
					'Camino'			=> 'Camino',
					'Netscape'			=> 'Netscape',
					'OmniWeb'			=> 'OmniWeb',
					'Safari'			=> 'Safari',
					'Mozilla'			=> 'Mozilla',
					'Konqueror'			=> 'Konqueror',
					'icab'				=> 'iCab',
					'Lynx'				=> 'Lynx',
					'Links'				=> 'Links',
					'hotjava'			=> 'HotJava',
					'amaya'				=> 'Amaya',
					'IBrowse'			=> 'IBrowse',
				);

$mobiles = array(
					// legacy array, old values commented out
					'mobileexplorer'	=> 'Mobile Explorer',
//					'openwave'			=> 'Open Wave',
//					'opera mini'		=> 'Opera Mini',
//					'operamini'			=> 'Opera Mini',
//					'elaine'			=> 'Palm',
					'palmsource'		=> 'Palm',
//					'digital paths'		=> 'Palm',
//					'avantgo'			=> 'Avantgo',
//					'xiino'				=> 'Xiino',
					'palmscape'			=> 'Palmscape',
//					'nokia'				=> 'Nokia',
//					'ericsson'			=> 'Ericsson',
//					'blackberry'		=> 'BlackBerry',
//					'motorola'			=> 'Motorola'

					// Phones and Manufacturers
					'kfapwi'			=> "Amazon Kindle Fire HDX 8.9 (3rd Gen)",
					'kfapwa'			=> "Amazon Kindle Fire HDX 8.9 (3rd Gen)",
					'kfthwi'			=> "Amazon Kindle Fire HDX 7 (3rd Gen)",
					'kfthwa'			=> "Amazon Kindle Fire HDX 7 (3rd Gen)",
					'kfsowi'			=> "Amazon Kindle Fire HD 7 (3rd Gen)",
					'kfjwi'				=> "Amazon Kindle Fire HD 8.9 (2nd Gen)",
					'kfjwa'				=> "Amazon Kindle Fire HD 8.9 (2nd Gen)",
					'silk/1.'			=> "Amazon Kindle Fire (1st Gen)",
					'kfot'				=> "Amazon Kindle Fire (2nd Gen)",
					'kftt'				=> "Amazon Kindle Fire HD 7 (2nd Gen)",
					'kftbwi'			=> "Amazon Fire HD 10 (5th Gen)",
					'kfmewi'			=> "Amazon Fire HD 8 (5th Gen)",
					'kffowi'			=> "Amazon Fire (5th Gen)",
					'kfsawi'			=> "Amazon Fire HDX 8.9 (4th Gen)",
					'kfsawa'			=> "Amazon Fire HDX 8.9 (4th Gen)",
					'kfaswi'			=> "Amazon Fire HD 7 (4th Gen)",
					'kfarwi'			=> "Amazon Fire HD 6 (4th Gen)",
					'sd4930ur'			=> "Amazon Fire Phone",
					
					'nexus 10'			=> "Nexus 10",
					'nexus 9'			=> "Nexus 9",
					'nexus 8'			=> "Nexus 8",
					'nexus 7'			=> "Nexus 7",
					'nexus 6'			=> "Nexus 6",
					'nexus 5'			=> "Nexus 5",
					'nexus 4'			=> "Nexus 4",
					'nexus 3'			=> "Nexus 3",
					'nexus 2'			=> "Nexus 2",
					'nexus 1'			=> "Nexus 1",
					'nexus'				=> "Nexus",
					
					'motorola'			=> "Motorola",
					
					'lumia 520'			=> "Nokia Lumia 520",
					'nokian9'			=> "Nokia N9",
					'nokia'				=> "Nokia",
					
					'palm'				=> "Palm",
					'iphone'			=> "Apple iPhone",
					'ipad'				=> "iPad",
					'ipod'				=> "Apple iPod Touch",
					'sony'				=> "Sony Ericsson",
					'ericsson'			=> "Sony Ericsson",
					
					'playbook'			=> 'BlackBerry PlayBook',
					'bb10'				=> "BlackBerry",
					'blackberry'		=> "BlackBerry",
					
					'cocoon'			=> "O2 Cocoon",
					'blazer'			=> "Treo",
					'lg'				=> "LG",
					'amoi'				=> "Amoi",
					'xda'				=> "XDA",
					'mda'				=> "MDA",
					'vario'				=> "Vario",
					'htc'				=> "HTC",
					
					'sm-n90'												=> "Samsung Galaxy Note III",
					'sch-i605'												=> "Samsung Galaxy Note II",
					'gt-n710'												=> "Samsung Galaxy Note II",
					'samsung gt-n7100/n7100xxufni2 build/kot49h'			=> "Samsung Galaxy Note II",
					'gt-i930'												=> "Samsung Galaxy S3",
					'samsung galaxy s3'										=> "Samsung Galaxy S3",
					'sc-06d'												=> "Samsung Galaxy S3",
					'sc-03e build/jro03c'									=> "Samsung Galaxy S3",
					'gt-i95'												=> "Samsung Galaxy S4",
					'sch-i545'												=> "Samsung Galaxy S4",
					'sph-l720'												=> "Samsung Galaxy S4",
					'sgh-m919'												=> "Samsung Galaxy S4",
					'sgh-i337'												=> "Samsung Galaxy S4",
					'shv-e300'												=> "Samsung Galaxy S4",
					'sc-04e'												=> "Samsung Galaxy S4",
					'gt-i919'												=> "Samsung Galaxy S4",
					'samsung galaxy s4'										=> "Samsung Galaxy S4",
					'samsung'												=> "Samsung",
					
					'sharp'				=> "Sharp",
					'sie-'				=> "Siemens",
					'alcatel'			=> "Alcatel",
					'benq'				=> "BenQ",
					'ipaq'				=> "HP iPaq",
					'mot-'				=> "Motorola",
					'playstation portable'	=> "PlayStation Portable",
					'hiptop'			=> "Danger Hiptop",
					'nec-'				=> "NEC",
					'panasonic'			=> "Panasonic",
					'philips'			=> "Philips",
					'sagem'				=> "Sagem",
					'sanyo'				=> "Sanyo",
					'spv'				=> "SPV",
					'zte'				=> "ZTE",
					'sendo'				=> "Sendo",

					// Operating Systems
					'symbian'				=> "Symbian",
					'SymbianOS'				=> "SymbianOS",
					'elaine'				=> "Palm",
					'palm'					=> "Palm",
					'series60'				=> "Symbian S60",
					'windows ce'			=> "Windows CE",
					'IBrowse'				=> 'IBrowse',
					'rim tablet os'			=> 'RIM Tablet OS',

					// Browsers
					'obigo'					=> "Obigo",
					'netfront'				=> "Netfront Browser",
					'openwave'				=> "Openwave Browser",
					'mobilexplorer'			=> "Mobile Explorer",
					'operamini'				=> "Opera Mini",
					'opera mini'			=> "Opera Mini",

					// Other
					'digital paths'			=> "Digital Paths",
					'avantgo'				=> "AvantGo",
					'xiino'					=> "Xiino",
					'novarra'				=> "Novarra Transcoder",
					'vodafone'				=> "Vodafone",
					'docomo'				=> "NTT DoCoMo",
					'o2'					=> "O2",

					// Fallback
					'mobile'				=> "Generic Mobile",
					'wireless'				=> "Generic Mobile",
					'j2me'					=> "Generic Mobile",
					'midp'					=> "Generic Mobile",
					'cldc'					=> "Generic Mobile",
					'up.link'				=> "Generic Mobile",
					'up.browser'			=> "Generic Mobile",
					'smartphone'			=> "Generic Mobile",
					'cellphone'				=> "Generic Mobile"
				);

// There are hundreds of bots but these are the most common.
$robots = array(
					'googlebot'			=> 'Googlebot',
					'msnbot'			=> 'MSNBot',
					'slurp'				=> 'Inktomi Slurp',
					'yahoo'				=> 'Yahoo',
					'askjeeves'			=> 'AskJeeves',
					'fastcrawler'		=> 'FastCrawler',
					'infoseek'			=> 'InfoSeek Robot 1.0',
					'lycos'				=> 'Lycos'
				);

/* End of file user_agents.php */
/* Location: ./application/config/user_agents.php */