
/*
 *********************************************************
 ---------------------------------------------------------
 Search
 ---------------------------------------------------------
 */

input[type=text].search-terms{
	min-width: 0px;
	width: 200px;
}
.search-term-highlight{
	
	color: <?= HIGHLIGHT_FONT_COLOR; ?>;
	<?= HIGHLIGHT_BACKGROUND; ?>
	
	font-weight:bold;
}

.live-visible{
	
}
.live-hidden{
	display: none !important;
}
.live-founded{
	background-color: rgba(255, 245, 202, 0.3);
}


.live-founded{
	
	animation: fade 1s forwards;
	-webkit-animation: fade 1s forwards;
	-moz-animation: fade 1s forwards;
	-o-animation: fade 1s forwards;
	animation-iteration-count: infinite;
	-webkit-animation-iteration-count: infinite;
	-moz-animation-iteration-count: infinite;	
	-o-animation-iteration-count: infinite;
}

.search-result{
	
	position: relative;
	display: block;
	
	border-bottom: thin solid <?= $vui->colors->vui_darker->rgba_s( 30 ); ?>;
	
	padding: <?= VUI_SPACING; ?>px;
	padding-bottom: 0;
	
}
.search-result > *:last-child:after{
	
	content: '';
	
	position: relative;
	display: block;
	
	clear: both;
	
	height: 0;
	overflow: hidden;
	
}
.search-result:hover,
.search-result.selected{
	
	background: <?= $vui->colors->vui_base->darken( 20, TRUE )->rgba_s( 12 ); ?>;
	
}
.search-result > .s1 > *{
	
	position: relative;
	display: block;
	
	color: <?= $vui->colors->vui_darker->hex_s; ?>;
	
	margin-bottom: <?= VUI_SPACING; ?>px;
	
}

/* ---------------------------------------------------- */
/* Plugin name separator */

.search-results .plugin-name{
	
	position: relative;
	display: block;
	
	font-size: 110%;
	font-weight: bold;
	
	padding: <?= VUI_SPACING; ?>px;
	
	background: <?= $vui->colors->vui_base->darken( 20, TRUE )->rgba_s( 20 ); ?>;
	
}

/* ---------------------------------------------------- */
/* Search results */

.search-results .plugin-wrapper,
.search-results .search-result-wrapper,
.search-results .plugin-search-results{
	
	display: block;
	padding: 0;
	margin: 0;
	
}

/* ---------------------------------------------------- */
/* Thumb */

.search-results .thumb{
	
	float: right;
	
	margin-left: <?= VUI_SPACING; ?>px;
	
}
.search-results .thumb .s1{
	
	position: relative;
	<?= $vui->css->display_inline_block(); ?>
	min-width: 10em;
	min-height: 10em;
	overflow: hidden;
	
	<?= $vui->css->border_radius( VUI_DEFAULT_BORDER_RADIUS . ' ' . VUI_DEFAULT_BORDER_RADIUS . ' 0 0' ); ?>
	
	<?= $vui->css->box_shadow( '0 0.2em 0.8em ' . $vui->colors->vui_base->darken( 20, TRUE )->rgba_s( 50 ) ); ?>
	
	border: 0.2em solid <?= $vui->colors->vui_lighter->hex_s; ?>;
	
	<?= $vui->css->transition( VUI_DEFAULT_TRANSITION ); ?>
	
}
.live-search-results .thumb .s1{
	
	min-width: 7em;
	min-height: 7em;
	
}
.search-results .thumb .s1:before{
	
	content: "";
	display: block;
	padding-top: 100%;
	
}
.search-result:hover .thumb .s1,
.search-result.selected .thumb .s1,
.search-results .thumb .s1:hover{
	
	<?= $vui->css->box_shadow( '0 0.7em 2em ' . $vui->colors->vui_base->darken( 30, TRUE )->rgba_s( 100 ) ); ?>
	
}
.search-results .thumb .s2{
	
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	width: 100%;
	
}
.search-results .thumb .s2 a{
	
	position: relative;
	width: 100%;
	height: 100%;
	
}
.search-results .thumb .s2 img{
	
	position: relative;
	width: 100%;
	
	<?= $vui->css->filter( array( 'saturate' => 0.3, ) ); ?>
	
}
.search-result:hover .thumb .s2 img,
.search-results .thumb:hover .s2 img,
.search-result.selected .thumb .s2 img{
	
	<?= $vui->css->filter( array( 'saturate' => 1, ) ); ?>
	
}

/* ---------------------------------------------------- */
/* Title */

.search-results .title{
	
	position: relative;
	display: block;
	
	margin-bottom: <?= VUI_SPACING; ?>px;
	
	font-size: 110%;
	font-weight: bold;
	
}

/* ---------------------------------------------------- */
/* Live search */

.qtip.live-search{
	
	min-width: 400px;
	max-width: 40%;
	width: 40%;
	
}
.qtip.live-search .search-results .plugins-names-wrapper,
.qtip.live-search .search-results .plugin-name,
.qtip.live-search .qtip-content{
	
	position: relative;
	display: block;
	
	padding: 0;
	margin: 0;
	
}
.qtip.live-search .qtip-content > .s1{
	
	position: relative;
	max-height: 500px;
	overflow: auto;
	
}

.qtip.live-search .search-results .plugins-names-wrapper{
	
	position: absolute;
	width: 10%;
	
}
.qtip.live-search .search-results .plugin-name .btn .text{
	
	display: none;
	
}
.qtip.live-search .search-results .plugin-wrapper{
	
	width: 90%;
	margin-left: 10%;
	
}

/*
 ---------------------------------------------------------
 Search
 ---------------------------------------------------------
 *********************************************************
 */
