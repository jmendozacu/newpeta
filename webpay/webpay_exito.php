<?php
session_start();
/****************************************************************/
// Modulo:	Validacion webpay para Joomla 1.5.x + Virtuemart
// Versión: 2.0
// Autor: 	Victor Araya Henriquez
// 			Ingeniero en Informatica 
// 			varaya_2000@yahoo.com
// Mejoras: validaciones adicionales para revición de check mac
/****************************************************************/
include("includes_webpay/configuration.php");
include("includes_webpay/database.php");
require_once( 'includes_webpay/phpmailer/class.phpmailer.php' );
$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
//$id=$_POST['TBK_ORDEN_COMPRA'];
$id=intval($_GET['valid']);
$query_RS_Busca = "select * from webpay where Tbk_orden_compra='".$id."' and Tbk_respuesta ='0' limit 0,1"; 
$database->setQuery( $query_RS_Busca );
$rows = $database->loadObjectList();

//echo $query_RS_Busca;
//exit();
if (count($rows)==0) {
	header("Location: webpay_fracaso.php");
	exit();
}

/*
, 

*/		
$row=$rows[0];
/*****Nombre comprador*******/
$query_RS_Busca = "SELECT * FROM `sales_flat_order` WHERE entity_id = ".$id; 
$database->setQuery( $query_RS_Busca );
$rows2 = $database->loadObjectList();
$comprador= htmlentities($rows2[0]->customer_firstname . " " .$rows2[0]->customer_lastname);

/******************/


$TBK_FINAL_NUMERO_TARJETA=$row->Tbk_numero_final_tarjeta;
$TBK_ORDEN_COMPRA=$id;
$Comercio="peta";
$url="http://www.peta.cl";
$trs_monto = substr($row->Tbk_monto,0,-3);
$dateArray=explode('-',$row->Tbk_fecha_transaccion);
$trs_fecha_transaccion = $dateArray[2]."/".$dateArray[1]."/".$dateArray[0]; 

//$trs_hora_transaccion = $_POST['TBK_HORA_TRANSACCION'];
$TBK_CODIGO_AUTORIZACION = $row->Tbk_codigo_autorizacion ;
$TIPO_TRANSACCION="Venta";
$trs_tipo_pago = $row->Tbk_tipo_pago; 
$trs_nro_cuotas = $row->Tbk_numero_cuotas;
if ($trs_nro_cuotas=='0'){$trs_nro_cuotas='00';}
$tipo_pago_descripcion="";
$tipo_pago=" Credito";
if ($trs_tipo_pago=="VN"){	$tipo_pago_descripcion=" Sin Cuotas";}
if ($trs_tipo_pago=="VC"){	$tipo_pago_descripcion=" Normales";}
if ($trs_tipo_pago=="SI"){	$tipo_pago_descripcion=" Sin inter&eacute;s";}
if ($trs_tipo_pago=="CIC"){	$tipo_pago_descripcion=" Cuotas Comercio";}
if ($trs_tipo_pago=="VD"){	$tipo_pago_descripcion=" Debito ";$tipo_pago=" Redcompra";}

$link_pedido="http://www.peta.cl/sales/order/view/order_id/".$id."";



//session_destroy();

?>
    


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Inicio de sesión del cliente</title><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /><meta name="description" content="" /><meta name="keywords" content="notebooks, discos duros, hp, samsung, toshiba, msi, tarjetas de video, tarjetas madre, ssd, proyectores, camaras, teclado, mouse, all in one, impresoras, plotters, ups, router, switch" /><meta name="robots" content="INDEX,FOLLOW" /><link rel="icon" href="https://www.peta.cl/media/favicon/default/favicon.ico" type="image/x-icon" /><link rel="shortcut icon" href="https://www.peta.cl/media/favicon/default/favicon.ico" type="image/x-icon" /> <!--[if lt IE 7]> <script type="text/javascript"> //<![CDATA[
    var BLANK_URL = 'https://www.peta.cl/js/blank.html';
    var BLANK_IMG = 'https://www.peta.cl/js/spacer.gif';
//]]> </script> <![endif]-->

<link type="text/css" rel="stylesheet" href="https://www.peta.cl/js/calendar/calendar-win2k-1.css" />
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/styles.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/widgets.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/style-newssubscribers.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/ekoim/allreviews/allreviews.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/peta/css/gomage/advanced-navigation.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/itemslider.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/generic-nav.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/brands/brands.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/ultra-slideshow/ultra-slideshow.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/itemgrid.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/accordion.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/ultra-megamenu/ultra-megamenu.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/ultra-megamenu/wide.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/qquoteadv.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/styles-infortis.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/generic-cck.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/dropdown.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/icons.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/tabs.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/icons-theme.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/icons-social.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/common.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/_config/design_base.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/override-components.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/override-modules.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/override-theme.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/grid12.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/_config/grid_base.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/_config/layout_base.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/custom.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/productquestions.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/cloud-zoom/cloud-zoom.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/infortis/_shared/colorbox.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/ecomdev/productpageshipping.css" media="all"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/fortis/default/css/print.css" media="print"/>
<link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/frontend/base/default/css/stockstatus.css" media="screen"/>
<script type="text/javascript" src="https://www.peta.cl/js/prototype/prototype.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/lib/ccard.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/prototype/validation.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/scriptaculous/builder.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/scriptaculous/effects.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/scriptaculous/dragdrop.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/scriptaculous/controls.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/scriptaculous/slider.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/varien/js.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/varien/form.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/mage/translate.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/mage/cookies.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/newssubscribers/jquery_1_4_2_min.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/newssubscribers/script.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/gomage/navigation/effects.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/gomage/advanced-navigation.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/jquery-1.7.2.min.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/jquery-noconflict.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.owlcarousel.min.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.easing.min.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.accordion.min.js" >
</script><script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.tabs.min.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.ba-throttle-debounce.min.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/varien/menu.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/varien/product.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/varien/configurable.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/calendar/calendar.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/calendar/calendar-setup.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.cloudzoom.min.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/js/infortis/jquery/plugins/jquery.colorbox.min.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/skin/frontend/fortis/peta/js/mask.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/skin/frontend/fortis/peta/js/rut-validate.js" ></script>
<script type="text/javascript" src="https://www.peta.cl/skin/frontend/base/default/js/qquoteadv.js" ></script>
<link rel="alternate" type="application/rss+xml" href="https://www.peta.cl/rss/catalog/new/store_id/1/" title="Productos nuevos"/>


<!--[if lt IE 7]> <script type="text/javascript" src="https://www.peta.cl/skin/extendware/ewminify/7e28fdde78570846.js" ></script><![endif]--> <!--[if IE]><link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/extendware/ewminify/f6633d2336b5b54d.css" media="all"/><![endif]--> <!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/extendware/ewminify/10cf0fba629a6f4c.css" media="all"/><![endif]--> <!--[if lte IE 8]><link type="text/css" rel="stylesheet" href="https://www.peta.cl/skin/extendware/ewminify/f6f936b70ad69dad.css" media="all"/><![endif]--> <script type="text/javascript"> //<![CDATA[
var infortisTheme = {}; infortisTheme.responsive = true; infortisTheme.maxBreak = 1360;
//]]> </script> <script type="text/javascript"> //<![CDATA[
Mage.Cookies.path     = '/';
Mage.Cookies.domain   = '.www.peta.cl';
//]]> </script> <script type="text/javascript"> //<![CDATA[
optionalZipCountries = ["AF","AL","DE","AD","AO","AI","AQ","AG","AN","SA","DZ","AR","AM","AW","AU","AT","AZ","BS","BH","BD","BB","BE","BZ","BJ","BM","BY","BO","BA","BW","BR","BN","BG","BF","BI","BT","CV","KH","CM","CA","TD","CL","CN","CY","VA","CO","KM","CG","KP","KR","CI","CR","HR","CU","DK","DM","EC","EG","SV","AE","ER","SK","SI","ES","US","EE","ET","PH","FI","FJ","FR","GA","GM","GE","GH","GI","GD","GR","GL","GP","GU","GT","GF","GG","GN","GW","GQ","GY","HT","HN","HU","IN","ID","IR","IQ","IE","BV","CX","IM","IS","NU","NF","AX","KY","CC","CK","FO","GS","HM","FK","MP","MH","UM","SB","TC","VG","VI","IL","IT","JM","JP","JE","JO","KZ","KE","KG","KI","KW","LA","LS","LV","LB","LR","LY","LI","LT","LU","MK","MG","MY","MW","MV","ML","MT","MA","MQ","MU","MR","YT","MX","FM","MD","MC","MN","ME","MS","MZ","MM","NA","NR","NP","NI","NE","NG","NO","NC","NZ","OM","NL","PK","PW","PA","PG","PY","PE","PN","PF","PL","PT","PR","QA","HK","MO","GB","CF","CZ","CD","DO","RE","RW","RO","RU","EH","WS","AS","BL","KN","SM","MF","PM","SH","LC","ST","VC","SN","RS","SC","SL","SG","SY","SO","LK","SZ","ZA","SD","SE","CH","SR","SJ","TH","TW","TZ","TJ","IO","PS","TF","TL","TG","TK","TO","TT","TN","TM","TR","TV","UA","UG","UY","UZ","VU","VE","VN","WF","YE","DJ","ZM","ZW"];
//]]> </script> <style type="text/css"> /* Buttons Color */
	.block-layered-nav .block-content button.button span span{
    
   color: #519CDE;
    
    		background-color: #FFFFFF;
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FFFFFF), to(#FFFFFF));
		background-image: -webkit-linear-gradient(top, #FFFFFF, #FFFFFF);
		background-image:    -moz-linear-gradient(top, #FFFFFF, #FFFFFF);
		background-image:      -o-linear-gradient(top, #FFFFFF, #FFFFFF);
		background-image:         linear-gradient(to bottom, #FFFFFF, #FFFFFF);
    
    	}  
		
	.gan-loadinfo{
		
				border-color:#000000 !important;
				
				color:#000000 !important;
				
				background-color:#FFFFFF !important;
				
				width:165px !important;
				
				height:68px !important;
				
				
	}
	/* Background Color */
	.block.block-layered-nav .block-content{
				background:#F3F3F3;
			}
	
	/* Slider Color */	
	.narrow-by-list .gan-slider-span{
				background:#1E90FF;
			}
	
	/* Popup Window Background */
	#gan-left-nav-main-container .filter-note-content-in,
	#gan-right-nav-main-container .filter-note-content-in,
    #gan-content-nav-main-container .filter-note-content-in,
	.narrow-by-list .filter-note-content-in{
				background:#FFFFFF;
			}
	
	/* Help Icon View */
	#gan-left-nav-main-container .filter-note-handle,
	#gan-right-nav-main-container .filter-note-handle,
    #gan-content-nav-main-container .filter-note-handle,
	.narrow-by-list .filter-note-handle{
				color:#1F5070;
			} </style> <script type="text/javascript"> //<![CDATA[


var GomageNavigation = new GomageNavigationClass({
						loadimage: 'https://www.peta.cl/media/extendware/ewminify/media/skin/3e/2/loadinfo.gif',
						loadimagealign: 'top',
			back_to_top_action: '0',
			gomage_navigation_loadinfo_text: "Cargando, por favor espere ....",
			
							gomage_navigation_urlhash: true,
			
		    			gan_shop_by_area: 5,
                        help_icon_open_type: 'over',
            
            scrolling_speed: '0',
		});
//]]> </script><style type="text/css"> .footer-container2
	{
		background-image: url(https://www.peta.cl/media/wysiwyg/infortis/fortis/_patterns/default/1.png);
	} </style><script type="text/javascript">//<![CDATA[
        var Translator = new Translate({"Please select an option.":"Por favor seleccione una opci\u00f3n.","This is a required field.":"Este es un campo obligatorio.","Please enter a valid number in this field.":"Por favor, introduzca un n\u00famero v\u00e1lido en este campo.","Please use letters only (a-z or A-Z) in this field.":"Usar \u00fanicamente letras (a-z o A-Z) en este campo por favor.","Please use only letters (a-z), numbers (0-9) or underscore(_) in this field, first character should be a letter.":"Por favor, use solo letras (a-z), n\u00fameros (0-9) o gui\u00f3n bajo (_) en este campo, el primer car\u00e1cter debe ser una letra.","Please enter a valid phone number. For example (123) 456-7890 or 123-456-7890.":"Por favor, introduzca un n\u00famero de tel\u00e9fono v\u00e1lido. Por ejemplo (123) 456-7890 o 123-456-7890.","Please enter a valid date.":"Por favor, introduzca una fecha v\u00e1lida.","Please enter a valid email address. For example johndoe@domain.com.":"Por favor, introduzca un Email v\u00e1lido. Por ejemplo juanperez@dominio.com.","Please enter 6 or more characters. Leading or trailing spaces will be ignored.":"Por favor, introduzca 6 o m\u00e1s caracteres. Los espacios entre caracteres ser\u00e1n ignorados.","Please make sure your passwords match.":"Por favor, aseg\u00farese de que sus contrase\u00f1as coinciden.","Please enter a valid URL. For example http:\/\/www.example.com or www.example.com":"Por favor, introduzca una URL v\u00e1lida. Por ejemplo http:\/\/www.example.com o www.example.com ","Please enter a valid social security number. For example 123-45-6789.":"Por favor, introduzca un n\u00famero de seguro social v\u00e1lido. Por ejemplo 123-45-6789.","Please enter a valid zip code. For example 90602 or 90602-1234.":"Por favor, introduzca un c\u00f3digo postal v\u00e1lido. Por ejemplo 90602 o 90602-1234.","Please enter a valid zip code.":"Por favor, introduzca un c\u00f3digo postal v\u00e1lido.","Please use this date format: dd\/mm\/yyyy. For example 17\/03\/2006 for the 17th of March, 2006.":"Por favor, use este formato de fecha: dd\/mm\/aaaa. Por ejemplo 17\/03\/2006 para el 17 de marzo de 2006. ","Please enter a valid $ amount. For example $100.00.":"Por favor, introduzca una cantidad en $ v\u00e1lida. Por ejemplo: $ 100.00.","Please select one of the above options.":"Por favor, elija una de las opciones de arriba.","Please select one of the options.":"Por favor, elija una de las opciones.","Please select State\/Province.":"Por favor, elija Estado\/Provincia.","Please enter a number greater than 0 in this field.":"Por favor, introduzca un n\u00famero superior a 0 en este campo.","Please enter a valid credit card number.":"Por favor, introduzca un n\u00famero de tarjeta de cr\u00e9dito v\u00e1lido.","Please wait, loading...":" Por favor, espere, cargando ...","Complete":"Completo","Add Products":"A\u00f1adir Productos","Please choose to register or to checkout as a guest":"Escoja registrarse o como invitado para realizar su pago por favor","Please specify payment method.":"Especifique m\u00e9todo de pago por favor."});
        //]]></script><meta name="language" content="spanish"><meta name="fb:page_id"  content="164296796959086"><meta name="copyright" content="&copy; 2011-2013 Peta.cl SpA."><link href='//fonts.googleapis.com/css?family=Open+Sans:600&amp;subset=latin' rel='stylesheet' type='text/css' /></head><body class=" customer-account-login "> <script type="text/javascript"> //<![CDATA[
    var _gaq = _gaq || [];
    
_gaq.push(['_setAccount', 'UA-16729596-1']);
_gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

//]]> </script><div id="root-wrapper"><div class="wrapper"> <noscript><div class="global-site-notice noscript"><div class="notice-inner"><p> <strong>JavaScript seems to be disabled in your browser.</strong><br /> You must have JavaScript enabled in your browser to utilize the functionality of this website.</p></div></div> </noscript><div class="page"><div class="header-container" id="top"><div class="header-container2"><div class="header-container3"><div class="header-top-container"><div class="header-top header container clearer"><div class="grid-full"><div class="search-wrapper search-wrapper-mobile search-wrapper-inline search-wrapper-small item item-right"><form id="search_mini_form" action="https://www.peta.cl/catalogsearch/result/" method="get"><div class="form-search"> <label for="search">Search:</label> <input id="search" type="text" name="q" value="" class="input-text" maxlength="128" /> <button type="submit" title="Buscar" class="button"><span><span>Buscar</span></span></button><div id="search_autocomplete" class="search-autocomplete"></div> <script type="text/javascript"> //<![CDATA[
            var searchForm = new Varien.searchForm('search_mini_form', 'search', 'Buscar aquí en toda la tienda...');
            searchForm.initAutocomplete('https://www.peta.cl/catalogsearch/ajax/suggest/', 'search_autocomplete');
        //]]> </script></div></form></div><div class="block_header_top_left item item-left"><div class="hide-below-768" title="Llámenos"> <span class="icon i-mobile-w no-bg-color"></span>Fono +562 23627577</div></div><div class="block_header_top_left2 item item-left"><div class="links-wrapper-separators"><ul class="links"><li class="hide-below-480"> <a href="http://www.peta.cl/preguntas_frecuentes" title="Preguntas Frecuentes">Preguntas Frecuentes (FAQ)</a></li><li class="hide-below-768"> <a href="http://www.peta.cl/contacts" title="Contáctenos">Cont&aacute;ctenos</a></li></ul></div></div></div></div></div><div class="header-primary-container"><div class="header-primary header container"><div class="grid-full"><div class="v-grid-container"><div class="logo-wrapper grid12-4 v-grid"> <a href="http://www.peta.cl/" title="Peta.cl" class="logo"><strong>Peta.cl</strong><img src="http://cloudfront-media.peta.cl/media/extendware/ewminify/media/skin/cd/5/peta.png" alt="Peta.cl" /></a></div><div class="user-menu clearer grid12-8 v-grid um-fortis um-no-icons um-animate-icons"><div class="user-menu-top clearer"><div class="item item-right hide-below-768"><p class="welcome-msg"><!--ewpagecache:welcome_message_block_begin:--><!--ewpagecache:welcome_message_begin:-->Bienvenido !<!--ewpagecache:welcome_message_end--><!--ewpagecache:welcome_message_block_end--></p></div></div><div id="mini-cart" class="dropdown is-empty hide-empty-cart"><div class="dropdown-toggle cover" title="Usted no tiene artículos en su carro de compras."><div class="feature-icon-hover"> <span class="first close-to-text force-no-bg-color icon i-cart-wb">&nbsp;</span><div class="name">Cart</div><div class="empty"><span class="price">$0</span></div> <span class="caret">&nbsp;</span></div></div><div class="dropdown-menu left-hand block"><div class="block-content-inner"><div class="empty">Usted no tiene artículos en su carro de compras.</div></div></div></div><div id="quick-compare" class="dropdown quick-compare is-empty"><div class="dropdown-toggle cover" title="No tiene artículos para comparar."><div class="feature-icon-hover"> <span class="first close-to-text force-no-bg-color icon i-compare-wb">&nbsp;</span><div class="name">Comparar</div><div class="amount">(0)</div> <span class="caret">&nbsp;</span></div></div><div class="dropdown-menu left-hand"><div class="empty">No tiene artículos para comparar.</div></div></div><div class="top-links"><ul class="links"><li class="first" ><a href="https://www.peta.cl/customer/account/" title="Mi cuenta" >Mi cuenta</a></li><li ><a href="https://www.peta.cl/wishlist/" title="Mi Lista de Deseos" >Mi Lista de Deseos</a></li><li ><a href="http://www.peta.cl/qquoteadv/index/" title="Cotizaciones" class="top-link-qquoteadv">Cotizaciones</a></li><li ><!--ewpagecache:toplink_login_begin:--><a href="https://www.peta.cl/customer/account/login/" title="Ingresar" >Ingresar</a><!--ewpagecache:toplink_login_end--></li><li class=" last" id="top-link-signup"><a href="https://www.peta.cl/customer/account/create/" title="Sign Up" >Sign Up</a></li></ul></div></div></div></div></div></div><div class="nav-container"><div class="nav container clearer stretched has-bg show-bg"><div id="mobnav" class="grid-full"> <a id="mobnav-trigger" href=""> <span class="trigger-icon"><span class="line"></span><span class="line"></span><span class="line"></span></span> <span>Menu</span> </a></div><ul class="accordion vertnav vertnav-top grid-full"><li class="level0 nav-1 level-top first parent"> <a href="http://www.peta.cl/accesorios.html" class="level-top"> <span>Accesorios</span> </a> <span class="opener">&nbsp;</span><ul class="level0"><li class="level1 nav-1-1 first parent"> <a href="http://www.peta.cl/accesorios/accesorios.html"> <span>Accesorios</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-1-1-1 first"> <a href="http://www.peta.cl/accesorios/accesorios/acc-camaras.html"> <span>Acc. Cámaras</span> </a></li><li class="level2 nav-1-1-2"> <a href="http://www.peta.cl/accesorios/accesorios/consolas-de-juego.html"> <span>Acc. Consolas</span> </a></li><li class="level2 nav-1-1-3"> <a href="http://www.peta.cl/accesorios/accesorios/acc-datacenter.html"> <span>Acc. Datacenter</span> </a></li><li class="level2 nav-1-1-4"> <a href="http://www.peta.cl/accesorios/accesorios/acc-escritorio.html"> <span>Acc. Escritorio</span> </a></li><li class="level2 nav-1-1-5"> <a href="http://www.peta.cl/accesorios/accesorios/acc-impresoras.html"> <span>Acc. Impresoras</span> </a></li><li class="level2 nav-1-1-6"> <a href="http://www.peta.cl/accesorios/accesorios/acc-monitores.html"> <span>Acc. Monitores</span> </a></li><li class="level2 nav-1-1-7"> <a href="http://www.peta.cl/accesorios/accesorios/acc-notebook.html"> <span>Acc. Notebook</span> </a></li><li class="level2 nav-1-1-8"> <a href="http://www.peta.cl/accesorios/accesorios/acc-audiovisual.html"> <span>Acc. Proyectores</span> </a></li><li class="level2 nav-1-1-9"> <a href="http://www.peta.cl/accesorios/accesorios/accesorios.html"> <span>Acc. Redes</span> </a></li><li class="level2 nav-1-1-10"> <a href="http://www.peta.cl/accesorios/accesorios/acc-servidores.html"> <span>Acc. Servidores</span> </a></li><li class="level2 nav-1-1-11"> <a href="http://www.peta.cl/accesorios/accesorios/acc-tablet.html"> <span>Acc. Tablet</span> </a></li><li class="level2 nav-1-1-12 last"> <a href="http://www.peta.cl/accesorios/accesorios/acc-ups.html"> <span>Acc. UPS</span> </a></li></ul></li><li class="level1 nav-1-2 parent"> <a href="http://www.peta.cl/accesorios/otros.html"> <span>Otros</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-1-2-13 first"> <a href="http://www.peta.cl/accesorios/otros/bolsos-y-mochilas.html"> <span>Bolsos y Mochilas</span> </a></li><li class="level2 nav-1-2-14"> <a href="http://www.peta.cl/accesorios/otros/cables.html"> <span>Cables</span> </a></li><li class="level2 nav-1-2-15 last"> <a href="http://www.peta.cl/accesorios/otros/open-box.html"> <span>Ofertas &amp; Open Box <span class="cat-label cat-label-label1">Ofertas</span></span> </a></li></ul></li><li class="level1 nav-1-3 parent"> <a href="http://www.peta.cl/accesorios/perifericos.html"> <span>Periféricos</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-1-3-16 first"> <a href="http://www.peta.cl/accesorios/perifericos/lector-codigo-barras.html"> <span>Lector Código Barras</span> </a></li><li class="level2 nav-1-3-17 parent"> <a href="http://www.peta.cl/accesorios/perifericos/mouse.html"> <span>Mouse</span> </a> <span class="opener">&nbsp;</span><ul class="level2"><li class="level3 nav-1-3-17-1 first last"> <a href="http://www.peta.cl/accesorios/perifericos/mouse/pad-mouse.html"> <span>Pad Mouse</span> </a></li></ul></li><li class="level2 nav-1-3-18 last"> <a href="http://www.peta.cl/accesorios/perifericos/teclados.html"> <span>Teclados</span> </a></li></ul></li><li class="level1 nav-1-4 parent"> <a href="http://www.peta.cl/accesorios/seguridad.html"> <span>Seguridad</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-1-4-19 first"> <a href="http://www.peta.cl/accesorios/seguridad/camaras-de-seguridad.html"> <span>Art. de Seguridad</span> </a></li><li class="level2 nav-1-4-20 last"> <a href="http://www.peta.cl/accesorios/seguridad/seguridad.html"> <span>Varios</span> </a></li></ul></li><li class="level1 nav-1-5 last parent"> <a href="http://www.peta.cl/accesorios/suministros-165.html"> <span>Suministros</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-1-5-21 first"> <a href="http://www.peta.cl/accesorios/suministros-165/toner-cartridges.html"> <span>Toner &amp; Cartridges</span> </a></li><li class="level2 nav-1-5-22"> <a href="http://www.peta.cl/accesorios/suministros-165/papeles.html"> <span>Papelería</span> </a></li><li class="level2 nav-1-5-23 last"> <a href="http://www.peta.cl/accesorios/suministros-165/cintas.html"> <span>Cintas</span> </a></li></ul></li></ul></li><li class="level0 nav-2 level-top parent"> <a href="http://www.peta.cl/foto-video.html" class="level-top"> <span>Audio &amp; Foto &amp; Video</span> </a> <span class="opener">&nbsp;</span><ul class="level0"><li class="level1 nav-2-1 first parent"> <a href="http://www.peta.cl/foto-video/audio.html"> <span>Audio</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-2-1-1 first"> <a href="http://www.peta.cl/foto-video/audio/audifonos.html"> <span>Audifonos</span> </a></li><li class="level2 nav-2-1-2"> <a href="http://www.peta.cl/foto-video/audio/parlantes.html"> <span>Parlantes</span> </a></li><li class="level2 nav-2-1-3"> <a href="http://www.peta.cl/foto-video/audio/mp3-mp4.html"> <span>Reproductores</span> </a></li><li class="level2 nav-2-1-4 last"> <a href="http://www.peta.cl/foto-video/audio/telefonos-celulares.html"> <span>Telefonos</span> </a></li></ul></li><li class="level1 nav-2-2 parent"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos.html"> <span>Cámaras de Fotos</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-2-2-5 first"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-dslr.html"> <span>Cámaras DSLR</span> </a></li><li class="level2 nav-2-2-6"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras.html"> <span>Cámaras Medio Formato</span> </a></li><li class="level2 nav-2-2-7"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-sin-espejos-milc.html"> <span>Cámaras MILC</span> </a></li><li class="level2 nav-2-2-8"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-point-shoot.html"> <span>Cámaras Point&amp;Shoot</span> </a></li><li class="level2 nav-2-2-9"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-bajo-el-agua.html"> <span>Cámaras bajo el Agua</span> </a></li><li class="level2 nav-2-2-10 last parent"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video.html"> <span>Cámaras con Rollo</span> </a> <span class="opener">&nbsp;</span><ul class="level2"><li class="level3 nav-2-2-10-1 first"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-35mm.html"> <span>Cámaras de 35mm</span> </a></li><li class="level3 nav-2-2-10-2"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-medio-formato.html"> <span>Cám. Medio Formato</span> </a></li><li class="level3 nav-2-2-10-3 last"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-gran-formato.html"> <span>Cám. Gran Formato</span> </a></li></ul></li></ul></li><li class="level1 nav-2-3 parent"> <a href="http://www.peta.cl/foto-video/camaras-de-video.html"> <span>Cámaras de Video</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-2-3-11 first"> <a href="http://www.peta.cl/foto-video/camaras-de-video/camaras-de-video.html"> <span>Cámaras de Video</span> </a></li><li class="level2 nav-2-3-12"> <a href="http://www.peta.cl/foto-video/camaras-de-video/camaras-web.html"> <span>Cámaras Web</span> </a></li><li class="level2 nav-2-3-13 last"> <a href="http://www.peta.cl/foto-video/camaras-de-video/gopro.html"> <span>GoPro</span> </a></li></ul></li><li class="level1 nav-2-4 parent"> <a href="http://www.peta.cl/foto-video/lentes.html"> <span>Lentes &amp; Flashes</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-2-4-14 first"> <a href="http://www.peta.cl/foto-video/lentes/flashes.html"> <span>Flashes</span> </a></li><li class="level2 nav-2-4-15"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-slr.html"> <span>Lentes Cámaras SLR</span> </a></li><li class="level2 nav-2-4-16"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-sin-espejo.html"> <span>Lentes Cám. MILC</span> </a></li><li class="level2 nav-2-4-17 last"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-telemetricas.html"> <span>Lentes Cám. Telemétricas</span> </a></li></ul></li><li class="level1 nav-2-5 last parent"> <a href="http://www.peta.cl/foto-video/vigilancia.html"> <span>Vigilancia &amp; GPS</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-2-5-18 first"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-ip.html"> <span>Cámaras IP</span> </a></li><li class="level2 nav-2-5-19"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-hd-analogas.html"> <span>Cám. HD &amp; Análogas</span> </a></li><li class="level2 nav-2-5-20"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-falsas.html"> <span>Cámaras Falsas</span> </a></li><li class="level2 nav-2-5-21"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-ocultas.html"> <span>Cámaras Ocultas</span> </a></li><li class="level2 nav-2-5-22"> <a href="http://www.peta.cl/foto-video/vigilancia/gps.html"> <span>GPS</span> </a></li><li class="level2 nav-2-5-23 last"> <a href="http://www.peta.cl/foto-video/vigilancia/monitores-de-bebes.html"> <span>Monitores de Bebes</span> </a></li></ul></li></ul></li><li class="level0 nav-3 level-top parent"> <a href="http://www.peta.cl/partes-y-piezas.html" class="level-top"> <span>Partes y Piezas</span> </a> <span class="opener">&nbsp;</span><ul class="level0"><li class="level1 nav-3-1 first parent"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento.html"> <span>Almacenamiento</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-1-1 first"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/arreglos-de-discos.html"> <span>Arreglos de Discos</span> </a></li><li class="level2 nav-3-1-2"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/unidades-opticas.html"> <span>CD &amp; DVD &amp; BR</span> </a></li><li class="level2 nav-3-1-3"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/externos.html"> <span>Discos Externos</span> </a></li><li class="level2 nav-3-1-4"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-internos.html"> <span>Discos Internos</span> </a></li><li class="level2 nav-3-1-5"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-duros-515.html"> <span>Discos p/ Servidor</span> </a></li><li class="level2 nav-3-1-6"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-de-estado-solido-ssd.html"> <span>Discos SSD</span> </a></li><li class="level2 nav-3-1-7 parent"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas.html"> <span>NAS &amp; Storage</span> </a> <span class="opener">&nbsp;</span><ul class="level2"><li class="level3 nav-3-1-7-1 first"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas/nas-desktop.html"> <span>NAS Desktop</span> </a></li><li class="level3 nav-3-1-7-2 last"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas/nas-rackmount.html"> <span>NAS Rackmount</span> </a></li></ul></li><li class="level2 nav-3-1-8"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/duplicadores.html"> <span>Duplicadores</span> </a></li><li class="level2 nav-3-1-9 last"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/pendrives-flash.html"> <span>Pendrives / Flash</span> </a></li></ul></li><li class="level1 nav-3-2 parent"> <a href="http://www.peta.cl/partes-y-piezas/display.html"> <span>Display</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-2-10 first"> <a href="http://www.peta.cl/partes-y-piezas/display/monitores.html"> <span>Monitores</span> </a></li><li class="level2 nav-3-2-11"> <a href="http://www.peta.cl/partes-y-piezas/display/scanners.html"> <span>Scanners</span> </a></li><li class="level2 nav-3-2-12"> <a href="http://www.peta.cl/partes-y-piezas/display/proyectores.html"> <span>Proyectores</span> </a></li><li class="level2 nav-3-2-13 parent"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video.html"> <span>Tarjetas de Video</span> </a> <span class="opener">&nbsp;</span><ul class="level2"><li class="level3 nav-3-2-13-3 first"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/dispositivos-video.html"> <span>Dispositivos Video</span> </a></li><li class="level3 nav-3-2-13-4"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-escritorio.html"> <span>Tarjetas Escritorio</span> </a></li><li class="level3 nav-3-2-13-5"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-gamers.html"> <span>Tarjetas Gamers</span> </a></li><li class="level3 nav-3-2-13-6 last"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-profesionales.html"> <span>Tarjetas Profesionales</span> </a></li></ul></li><li class="level2 nav-3-2-14 last"> <a href="http://www.peta.cl/partes-y-piezas/display/televisores.html"> <span>Televisores</span> </a></li></ul></li><li class="level1 nav-3-3 parent"> <a href="http://www.peta.cl/partes-y-piezas/componentes.html"> <span>Componentes</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-3-15 first"> <a href="http://www.peta.cl/partes-y-piezas/componentes/procesadores.html"> <span>CPU PC Escritorio</span> </a></li><li class="level2 nav-3-3-16"> <a href="http://www.peta.cl/partes-y-piezas/componentes/procesadores-514.html"> <span>CPU Servidores</span> </a></li><li class="level2 nav-3-3-17"> <a href="http://www.peta.cl/partes-y-piezas/componentes/controladoras.html"> <span>Controladoras</span> </a></li><li class="level2 nav-3-3-18"> <a href="http://www.peta.cl/partes-y-piezas/componentes/fuentes-de-poder.html"> <span>Fuentes de Poder</span> </a></li><li class="level2 nav-3-3-19"> <a href="http://www.peta.cl/partes-y-piezas/componentes/gabinetes.html"> <span>Gabinetes</span> </a></li><li class="level2 nav-3-3-20"> <a href="http://www.peta.cl/partes-y-piezas/componentes/memorias.html"> <span>Memorias</span> </a></li><li class="level2 nav-3-3-21 last parent"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre.html"> <span>Placas Madre</span> </a> <span class="opener">&nbsp;</span><ul class="level2"><li class="level3 nav-3-3-21-7 first"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre/amd.html"> <span>AMD</span> </a></li><li class="level3 nav-3-3-21-8 last"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre/intel.html"> <span>Intel</span> </a></li></ul></li></ul></li><li class="level1 nav-3-4 parent"> <a href="http://www.peta.cl/partes-y-piezas/impresoras.html"> <span>Impresoras</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-4-22 first"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/inyeccion-de-tinta.html"> <span>Inyección de tinta</span> </a></li><li class="level2 nav-3-4-23"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/http-laserwaca-com.html"> <span>Laser</span> </a></li><li class="level2 nav-3-4-24"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/matriz-de-punto.html"> <span>Matriz de Punto</span> </a></li><li class="level2 nav-3-4-25"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/plotters.html"> <span>Plotters</span> </a></li><li class="level2 nav-3-4-26"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/print-server.html"> <span>Print Server</span> </a></li><li class="level2 nav-3-4-27 last"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/termicas.html"> <span>Térmicas / POS</span> </a></li></ul></li><li class="level1 nav-3-5 parent"> <a href="http://www.peta.cl/partes-y-piezas/redes.html"> <span>Redes</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-5-28 first"> <a href="http://www.peta.cl/partes-y-piezas/redes/access-point.html"> <span>Access Point</span> </a></li><li class="level2 nav-3-5-29"> <a href="http://www.peta.cl/partes-y-piezas/redes/firewall.html"> <span>Firewall</span> </a></li><li class="level2 nav-3-5-30"> <a href="http://www.peta.cl/partes-y-piezas/redes/kvm.html"> <span>KVM</span> </a></li><li class="level2 nav-3-5-31"> <a href="http://www.peta.cl/partes-y-piezas/redes/otros.html"> <span>Otros</span> </a></li><li class="level2 nav-3-5-32"> <a href="http://www.peta.cl/partes-y-piezas/redes/routers-switches.html"> <span>Routers</span> </a></li><li class="level2 nav-3-5-33"> <a href="http://www.peta.cl/partes-y-piezas/redes/switches.html"> <span>Switches</span> </a></li><li class="level2 nav-3-5-34"> <a href="http://www.peta.cl/partes-y-piezas/redes/tarjetas-de-red.html"> <span>Tarjetas de Red</span> </a></li><li class="level2 nav-3-5-35 last"> <a href="http://www.peta.cl/partes-y-piezas/redes/wireless.html"> <span>Wireless</span> </a></li></ul></li><li class="level1 nav-3-6 parent"> <a href="http://www.peta.cl/partes-y-piezas/software.html"> <span>Software</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-3-6-36 first"> <a href="http://www.peta.cl/partes-y-piezas/software/antivirus.html"> <span>Antivirus</span> </a></li><li class="level2 nav-3-6-37"> <a href="http://www.peta.cl/partes-y-piezas/software/sistemas-operativos.html"> <span>Sistemas Operativos</span> </a></li><li class="level2 nav-3-6-38"> <a href="http://www.peta.cl/partes-y-piezas/software/office.html"> <span>Office</span> </a></li><li class="level2 nav-3-6-39 last"> <a href="http://www.peta.cl/partes-y-piezas/software/otros.html"> <span>Otras Apl.</span> </a></li></ul></li><li class="level1 nav-3-7 last"> <a href="http://www.peta.cl/partes-y-piezas/ups.html"> <span>UPS &amp; Reguladores</span> </a></li></ul></li><li class="level0 nav-4 level-top parent"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks.html" class="level-top"> <span>Equipos</span> </a> <span class="opener">&nbsp;</span><ul class="level0"><li class="level1 nav-4-1 first parent"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos.html"> <span>Fijos</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-4-1-1 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/desktops.html"> <span>PC Escritorio</span> </a></li><li class="level2 nav-4-1-2"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/pos.html"> <span>POS</span> </a></li><li class="level2 nav-4-1-3"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/terminal-de-red.html"> <span>Mini-PC &amp; Terminales</span> </a></li><li class="level2 nav-4-1-4 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/todo-en-uno-aio2.html"> <span>Todo en Uno (AIO)</span> </a></li></ul></li><li class="level1 nav-4-2 parent"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles.html"> <span>Móviles</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-4-2-5 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/e-book.html"> <span>E-Book</span> </a></li><li class="level2 nav-4-2-6"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/notebooks.html"> <span>Notebooks</span> </a></li><li class="level2 nav-4-2-7"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/tablets.html"> <span>Tablets</span> </a></li><li class="level2 nav-4-2-8 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/ultrabooks.html"> <span>Ultrabooks</span> </a></li></ul></li><li class="level1 nav-4-3 last parent"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores.html"> <span>Servidores</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-4-3-9 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores/accesorios.html"> <span>Accesorios</span> </a></li><li class="level2 nav-4-3-10 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores/servidores2.html"> <span>Servidores</span> </a></li></ul></li></ul></li><li class="level0 nav-5 level-top last parent"> <a href="http://www.peta.cl/apple.html" class="level-top"> <span>Apple <span class="cat-label cat-label-label2 pin-bottom">Mac !</span></span> </a> <span class="opener">&nbsp;</span><ul class="level0"><li class="level1 nav-5-1 first parent"> <a href="http://www.peta.cl/apple/mac.html"> <span>MacBook</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-5-1-1 first"> <a href="http://www.peta.cl/apple/mac/macbook-air.html"> <span>MacBook Air</span> </a></li><li class="level2 nav-5-1-2"> <a href="http://www.peta.cl/apple/mac/macbook-pro-retina.html"> <span>MacBook Pro Retina</span> </a></li><li class="level2 nav-5-1-3 last"> <a href="http://www.peta.cl/apple/mac/macbook-pro.html"> <span>MacBook Pro</span> </a></li></ul></li><li class="level1 nav-5-2 parent"> <a href="http://www.peta.cl/apple/imac.html"> <span>iMac &amp; MacPro</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-5-2-4 first"> <a href="http://www.peta.cl/apple/imac/mac-mini.html"> <span>Mac mini</span> </a></li><li class="level2 nav-5-2-5"> <a href="http://www.peta.cl/apple/imac/mac-pro.html"> <span>Mac Pro</span> </a></li><li class="level2 nav-5-2-6 last"> <a href="http://www.peta.cl/apple/imac/imac.html"> <span>iMac</span> </a></li></ul></li><li class="level1 nav-5-3 parent"> <a href="http://www.peta.cl/apple/ipad.html"> <span>iPad</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-5-3-7 first"> <a href="http://www.peta.cl/apple/ipad/ipad-mini.html"> <span>iPad mini</span> </a></li><li class="level2 nav-5-3-8"> <a href="http://www.peta.cl/apple/ipad/ipad-mini-retina.html"> <span>iPad mini (Retina)</span> </a></li><li class="level2 nav-5-3-9"> <a href="http://www.peta.cl/apple/ipad/ipad-2.html"> <span>iPad 2</span> </a></li><li class="level2 nav-5-3-10"> <a href="http://www.peta.cl/apple/ipad/ipad-4-retina.html"> <span>iPad 4 (Retina)</span> </a></li><li class="level2 nav-5-3-11 last"> <a href="http://www.peta.cl/apple/ipad/ipad-air.html"> <span>iPad Air</span> </a></li></ul></li><li class="level1 nav-5-4 parent"> <a href="http://www.peta.cl/apple/ipod.html"> <span>iPod</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-5-4-12 first"> <a href="http://www.peta.cl/apple/ipod/ipod-classic.html"> <span>iPod classic</span> </a></li><li class="level2 nav-5-4-13"> <a href="http://www.peta.cl/apple/ipod/ipod-touch.html"> <span>iPod touch</span> </a></li><li class="level2 nav-5-4-14"> <a href="http://www.peta.cl/apple/ipod/ipod-nano.html"> <span>iPod nano</span> </a></li><li class="level2 nav-5-4-15 last"> <a href="http://www.peta.cl/apple/ipod/ipod-shuffle.html"> <span>iPod shuffle</span> </a></li></ul></li><li class="level1 nav-5-5 last parent"> <a href="http://www.peta.cl/apple/accesorios.html"> <span>Accesorios</span> </a> <span class="opener">&nbsp;</span><ul class="level1"><li class="level2 nav-5-5-16 first"> <a href="http://www.peta.cl/apple/accesorios/almacenamiento.html"> <span>Almacenamiento</span> </a></li><li class="level2 nav-5-5-17"> <a href="http://www.peta.cl/apple/accesorios/wireless-connectivity.html"> <span>Conectividad</span> </a></li><li class="level2 nav-5-5-18"> <a href="http://www.peta.cl/apple/accesorios/accesorios-ipad.html"> <span>iPad</span> </a></li><li class="level2 nav-5-5-19"> <a href="http://www.peta.cl/apple/accesorios/macbook-accessories.html"> <span>MacBook</span> </a></li><li class="level2 nav-5-5-20"> <a href="http://www.peta.cl/apple/accesorios/monitores.html"> <span>Monitores &amp; Video</span> </a></li><li class="level2 nav-5-5-21"> <a href="http://www.peta.cl/apple/accesorios/miscellaneous-accessories.html"> <span>Otros</span> </a></li><li class="level2 nav-5-5-22"> <a href="http://www.peta.cl/apple/accesorios/perifericos.html"> <span>Periféricos</span> </a></li><li class="level2 nav-5-5-23 last"> <a href="http://www.peta.cl/apple/accesorios/software.html"> <span>Software</span> </a></li></ul></li></ul></li></ul><ul id="nav" class="grid-full wide"><li id="homelink-icon" class="level0 level-top"> <a class="level-top feature feature-icon-hover" href="http://www.peta.cl/"><span class="icon i-home-w force-no-bg-color"></span></a></li><li class="level0 nav-6 level-top first parent"> <a href="http://www.peta.cl/accesorios.html" class="level-top"> <span>Accesorios</span><span class="caret">&nbsp;</span> </a><div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2"><div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-6col"><ul class="level0"><li class="level1 nav-6-1 first parent item"> <a href="http://www.peta.cl/accesorios/accesorios.html"> <span>Accesorios</span> </a><ul class="level1"><li class="level2 nav-6-1-1 first"> <a href="http://www.peta.cl/accesorios/accesorios/acc-camaras.html"> <span>Acc. Cámaras</span> </a></li><li class="level2 nav-6-1-2"> <a href="http://www.peta.cl/accesorios/accesorios/consolas-de-juego.html"> <span>Acc. Consolas</span> </a></li><li class="level2 nav-6-1-3"> <a href="http://www.peta.cl/accesorios/accesorios/acc-datacenter.html"> <span>Acc. Datacenter</span> </a></li><li class="level2 nav-6-1-4"> <a href="http://www.peta.cl/accesorios/accesorios/acc-escritorio.html"> <span>Acc. Escritorio</span> </a></li><li class="level2 nav-6-1-5"> <a href="http://www.peta.cl/accesorios/accesorios/acc-impresoras.html"> <span>Acc. Impresoras</span> </a></li><li class="level2 nav-6-1-6"> <a href="http://www.peta.cl/accesorios/accesorios/acc-monitores.html"> <span>Acc. Monitores</span> </a></li><li class="level2 nav-6-1-7"> <a href="http://www.peta.cl/accesorios/accesorios/acc-notebook.html"> <span>Acc. Notebook</span> </a></li><li class="level2 nav-6-1-8"> <a href="http://www.peta.cl/accesorios/accesorios/acc-audiovisual.html"> <span>Acc. Proyectores</span> </a></li><li class="level2 nav-6-1-9"> <a href="http://www.peta.cl/accesorios/accesorios/accesorios.html"> <span>Acc. Redes</span> </a></li><li class="level2 nav-6-1-10"> <a href="http://www.peta.cl/accesorios/accesorios/acc-servidores.html"> <span>Acc. Servidores</span> </a></li><li class="level2 nav-6-1-11"> <a href="http://www.peta.cl/accesorios/accesorios/acc-tablet.html"> <span>Acc. Tablet</span> </a></li><li class="level2 nav-6-1-12 last"> <a href="http://www.peta.cl/accesorios/accesorios/acc-ups.html"> <span>Acc. UPS</span> </a></li></ul></li><li class="level1 nav-6-2 parent item"> <a href="http://www.peta.cl/accesorios/otros.html"> <span>Otros</span> </a><ul class="level1"><li class="level2 nav-6-2-13 first"> <a href="http://www.peta.cl/accesorios/otros/bolsos-y-mochilas.html"> <span>Bolsos y Mochilas</span> </a></li><li class="level2 nav-6-2-14"> <a href="http://www.peta.cl/accesorios/otros/cables.html"> <span>Cables</span> </a></li><li class="level2 nav-6-2-15 last"> <a href="http://www.peta.cl/accesorios/otros/open-box.html"> <span>Ofertas &amp; Open Box <span class="cat-label cat-label-label1">Ofertas</span></span> </a></li></ul></li><li class="level1 nav-6-3 parent item"> <a href="http://www.peta.cl/accesorios/perifericos.html"> <span>Periféricos</span> </a><ul class="level1"><li class="level2 nav-6-3-16 first"> <a href="http://www.peta.cl/accesorios/perifericos/lector-codigo-barras.html"> <span>Lector Código Barras</span> </a></li><li class="level2 nav-6-3-17 parent"> <a href="http://www.peta.cl/accesorios/perifericos/mouse.html"> <span>Mouse</span> </a><ul class="level2"><li class="level3 nav-6-3-17-1 first last"> <a href="http://www.peta.cl/accesorios/perifericos/mouse/pad-mouse.html"> <span>Pad Mouse</span> </a></li></ul></li><li class="level2 nav-6-3-18 last"> <a href="http://www.peta.cl/accesorios/perifericos/teclados.html"> <span>Teclados</span> </a></li></ul></li><li class="level1 nav-6-4 parent item"> <a href="http://www.peta.cl/accesorios/seguridad.html"> <span>Seguridad</span> </a><ul class="level1"><li class="level2 nav-6-4-19 first"> <a href="http://www.peta.cl/accesorios/seguridad/camaras-de-seguridad.html"> <span>Art. de Seguridad</span> </a></li><li class="level2 nav-6-4-20 last"> <a href="http://www.peta.cl/accesorios/seguridad/seguridad.html"> <span>Varios</span> </a></li></ul></li><li class="level1 nav-6-5 last parent item"> <a href="http://www.peta.cl/accesorios/suministros-165.html"> <span>Suministros</span> </a><ul class="level1"><li class="level2 nav-6-5-21 first"> <a href="http://www.peta.cl/accesorios/suministros-165/toner-cartridges.html"> <span>Toner &amp; Cartridges</span> </a></li><li class="level2 nav-6-5-22"> <a href="http://www.peta.cl/accesorios/suministros-165/papeles.html"> <span>Papelería</span> </a></li><li class="level2 nav-6-5-23 last"> <a href="http://www.peta.cl/accesorios/suministros-165/cintas.html"> <span>Cintas</span> </a></li></ul></li></ul></div></div></div></li><li class="level0 nav-7 level-top parent"> <a href="http://www.peta.cl/foto-video.html" class="level-top"> <span>Audio &amp; Foto &amp; Video</span><span class="caret">&nbsp;</span> </a><div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2"><div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-6col"><ul class="level0"><li class="level1 nav-7-1 first parent item"> <a href="http://www.peta.cl/foto-video/audio.html"> <span>Audio</span> </a><ul class="level1"><li class="level2 nav-7-1-1 first"> <a href="http://www.peta.cl/foto-video/audio/audifonos.html"> <span>Audifonos</span> </a></li><li class="level2 nav-7-1-2"> <a href="http://www.peta.cl/foto-video/audio/parlantes.html"> <span>Parlantes</span> </a></li><li class="level2 nav-7-1-3"> <a href="http://www.peta.cl/foto-video/audio/mp3-mp4.html"> <span>Reproductores</span> </a></li><li class="level2 nav-7-1-4 last"> <a href="http://www.peta.cl/foto-video/audio/telefonos-celulares.html"> <span>Telefonos</span> </a></li></ul></li><li class="level1 nav-7-2 parent item"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos.html"> <span>Cámaras de Fotos</span> </a><ul class="level1"><li class="level2 nav-7-2-5 first"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-dslr.html"> <span>Cámaras DSLR</span> </a></li><li class="level2 nav-7-2-6"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras.html"> <span>Cámaras Medio Formato</span> </a></li><li class="level2 nav-7-2-7"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-sin-espejos-milc.html"> <span>Cámaras MILC</span> </a></li><li class="level2 nav-7-2-8"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-point-shoot.html"> <span>Cámaras Point&amp;Shoot</span> </a></li><li class="level2 nav-7-2-9"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-bajo-el-agua.html"> <span>Cámaras bajo el Agua</span> </a></li><li class="level2 nav-7-2-10 last parent"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video.html"> <span>Cámaras con Rollo</span> </a><ul class="level2"><li class="level3 nav-7-2-10-1 first"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-35mm.html"> <span>Cámaras de 35mm</span> </a></li><li class="level3 nav-7-2-10-2"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-medio-formato.html"> <span>Cám. Medio Formato</span> </a></li><li class="level3 nav-7-2-10-3 last"> <a href="http://www.peta.cl/foto-video/camaras-de-fotos/camaras-de-video/camaras-de-gran-formato.html"> <span>Cám. Gran Formato</span> </a></li></ul></li></ul></li><li class="level1 nav-7-3 parent item"> <a href="http://www.peta.cl/foto-video/camaras-de-video.html"> <span>Cámaras de Video</span> </a><ul class="level1"><li class="level2 nav-7-3-11 first"> <a href="http://www.peta.cl/foto-video/camaras-de-video/camaras-de-video.html"> <span>Cámaras de Video</span> </a></li><li class="level2 nav-7-3-12"> <a href="http://www.peta.cl/foto-video/camaras-de-video/camaras-web.html"> <span>Cámaras Web</span> </a></li><li class="level2 nav-7-3-13 last"> <a href="http://www.peta.cl/foto-video/camaras-de-video/gopro.html"> <span>GoPro</span> </a></li></ul></li><li class="level1 nav-7-4 parent item"> <a href="http://www.peta.cl/foto-video/lentes.html"> <span>Lentes &amp; Flashes</span> </a><ul class="level1"><li class="level2 nav-7-4-14 first"> <a href="http://www.peta.cl/foto-video/lentes/flashes.html"> <span>Flashes</span> </a></li><li class="level2 nav-7-4-15"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-slr.html"> <span>Lentes Cámaras SLR</span> </a></li><li class="level2 nav-7-4-16"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-sin-espejo.html"> <span>Lentes Cám. MILC</span> </a></li><li class="level2 nav-7-4-17 last"> <a href="http://www.peta.cl/foto-video/lentes/lentes-para-camaras-telemetricas.html"> <span>Lentes Cám. Telemétricas</span> </a></li></ul></li><li class="level1 nav-7-5 last parent item"> <a href="http://www.peta.cl/foto-video/vigilancia.html"> <span>Vigilancia &amp; GPS</span> </a><ul class="level1"><li class="level2 nav-7-5-18 first"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-ip.html"> <span>Cámaras IP</span> </a></li><li class="level2 nav-7-5-19"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-hd-analogas.html"> <span>Cám. HD &amp; Análogas</span> </a></li><li class="level2 nav-7-5-20"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-falsas.html"> <span>Cámaras Falsas</span> </a></li><li class="level2 nav-7-5-21"> <a href="http://www.peta.cl/foto-video/vigilancia/camaras-ocultas.html"> <span>Cámaras Ocultas</span> </a></li><li class="level2 nav-7-5-22"> <a href="http://www.peta.cl/foto-video/vigilancia/gps.html"> <span>GPS</span> </a></li><li class="level2 nav-7-5-23 last"> <a href="http://www.peta.cl/foto-video/vigilancia/monitores-de-bebes.html"> <span>Monitores de Bebes</span> </a></li></ul></li></ul></div></div></div></li><li class="level0 nav-8 level-top parent"> <a href="http://www.peta.cl/partes-y-piezas.html" class="level-top"> <span>Partes y Piezas</span><span class="caret">&nbsp;</span> </a><div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2"><div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-6col"><ul class="level0"><li class="level1 nav-8-1 first parent item"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento.html"> <span>Almacenamiento</span> </a><ul class="level1"><li class="level2 nav-8-1-1 first"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/arreglos-de-discos.html"> <span>Arreglos de Discos</span> </a></li><li class="level2 nav-8-1-2"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/unidades-opticas.html"> <span>CD &amp; DVD &amp; BR</span> </a></li><li class="level2 nav-8-1-3"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/externos.html"> <span>Discos Externos</span> </a></li><li class="level2 nav-8-1-4"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-internos.html"> <span>Discos Internos</span> </a></li><li class="level2 nav-8-1-5"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-duros-515.html"> <span>Discos p/ Servidor</span> </a></li><li class="level2 nav-8-1-6"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/discos-de-estado-solido-ssd.html"> <span>Discos SSD</span> </a></li><li class="level2 nav-8-1-7 parent"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas.html"> <span>NAS &amp; Storage</span> </a><ul class="level2"><li class="level3 nav-8-1-7-1 first"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas/nas-desktop.html"> <span>NAS Desktop</span> </a></li><li class="level3 nav-8-1-7-2 last"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/nas/nas-rackmount.html"> <span>NAS Rackmount</span> </a></li></ul></li><li class="level2 nav-8-1-8"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/duplicadores.html"> <span>Duplicadores</span> </a></li><li class="level2 nav-8-1-9 last"> <a href="http://www.peta.cl/partes-y-piezas/almacenamiento/pendrives-flash.html"> <span>Pendrives / Flash</span> </a></li></ul></li><li class="level1 nav-8-2 parent item"> <a href="http://www.peta.cl/partes-y-piezas/display.html"> <span>Display</span> </a><ul class="level1"><li class="level2 nav-8-2-10 first"> <a href="http://www.peta.cl/partes-y-piezas/display/monitores.html"> <span>Monitores</span> </a></li><li class="level2 nav-8-2-11"> <a href="http://www.peta.cl/partes-y-piezas/display/scanners.html"> <span>Scanners</span> </a></li><li class="level2 nav-8-2-12"> <a href="http://www.peta.cl/partes-y-piezas/display/proyectores.html"> <span>Proyectores</span> </a></li><li class="level2 nav-8-2-13 parent"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video.html"> <span>Tarjetas de Video</span> </a><ul class="level2"><li class="level3 nav-8-2-13-3 first"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/dispositivos-video.html"> <span>Dispositivos Video</span> </a></li><li class="level3 nav-8-2-13-4"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-escritorio.html"> <span>Tarjetas Escritorio</span> </a></li><li class="level3 nav-8-2-13-5"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-gamers.html"> <span>Tarjetas Gamers</span> </a></li><li class="level3 nav-8-2-13-6 last"> <a href="http://www.peta.cl/partes-y-piezas/display/tarjetas-de-video/tarjetas-profesionales.html"> <span>Tarjetas Profesionales</span> </a></li></ul></li><li class="level2 nav-8-2-14 last"> <a href="http://www.peta.cl/partes-y-piezas/display/televisores.html"> <span>Televisores</span> </a></li></ul></li><li class="level1 nav-8-3 parent item"> <a href="http://www.peta.cl/partes-y-piezas/componentes.html"> <span>Componentes</span> </a><ul class="level1"><li class="level2 nav-8-3-15 first"> <a href="http://www.peta.cl/partes-y-piezas/componentes/procesadores.html"> <span>CPU PC Escritorio</span> </a></li><li class="level2 nav-8-3-16"> <a href="http://www.peta.cl/partes-y-piezas/componentes/procesadores-514.html"> <span>CPU Servidores</span> </a></li><li class="level2 nav-8-3-17"> <a href="http://www.peta.cl/partes-y-piezas/componentes/controladoras.html"> <span>Controladoras</span> </a></li><li class="level2 nav-8-3-18"> <a href="http://www.peta.cl/partes-y-piezas/componentes/fuentes-de-poder.html"> <span>Fuentes de Poder</span> </a></li><li class="level2 nav-8-3-19"> <a href="http://www.peta.cl/partes-y-piezas/componentes/gabinetes.html"> <span>Gabinetes</span> </a></li><li class="level2 nav-8-3-20"> <a href="http://www.peta.cl/partes-y-piezas/componentes/memorias.html"> <span>Memorias</span> </a></li><li class="level2 nav-8-3-21 last parent"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre.html"> <span>Placas Madre</span> </a><ul class="level2"><li class="level3 nav-8-3-21-7 first"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre/amd.html"> <span>AMD</span> </a></li><li class="level3 nav-8-3-21-8 last"> <a href="http://www.peta.cl/partes-y-piezas/componentes/placas-madre/intel.html"> <span>Intel</span> </a></li></ul></li></ul></li><li class="level1 nav-8-4 parent item"> <a href="http://www.peta.cl/partes-y-piezas/impresoras.html"> <span>Impresoras</span> </a><ul class="level1"><li class="level2 nav-8-4-22 first"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/inyeccion-de-tinta.html"> <span>Inyección de tinta</span> </a></li><li class="level2 nav-8-4-23"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/http-laserwaca-com.html"> <span>Laser</span> </a></li><li class="level2 nav-8-4-24"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/matriz-de-punto.html"> <span>Matriz de Punto</span> </a></li><li class="level2 nav-8-4-25"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/plotters.html"> <span>Plotters</span> </a></li><li class="level2 nav-8-4-26"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/print-server.html"> <span>Print Server</span> </a></li><li class="level2 nav-8-4-27 last"> <a href="http://www.peta.cl/partes-y-piezas/impresoras/termicas.html"> <span>Térmicas / POS</span> </a></li></ul></li><li class="level1 nav-8-5 parent item"> <a href="http://www.peta.cl/partes-y-piezas/redes.html"> <span>Redes</span> </a><ul class="level1"><li class="level2 nav-8-5-28 first"> <a href="http://www.peta.cl/partes-y-piezas/redes/access-point.html"> <span>Access Point</span> </a></li><li class="level2 nav-8-5-29"> <a href="http://www.peta.cl/partes-y-piezas/redes/firewall.html"> <span>Firewall</span> </a></li><li class="level2 nav-8-5-30"> <a href="http://www.peta.cl/partes-y-piezas/redes/kvm.html"> <span>KVM</span> </a></li><li class="level2 nav-8-5-31"> <a href="http://www.peta.cl/partes-y-piezas/redes/otros.html"> <span>Otros</span> </a></li><li class="level2 nav-8-5-32"> <a href="http://www.peta.cl/partes-y-piezas/redes/routers-switches.html"> <span>Routers</span> </a></li><li class="level2 nav-8-5-33"> <a href="http://www.peta.cl/partes-y-piezas/redes/switches.html"> <span>Switches</span> </a></li><li class="level2 nav-8-5-34"> <a href="http://www.peta.cl/partes-y-piezas/redes/tarjetas-de-red.html"> <span>Tarjetas de Red</span> </a></li><li class="level2 nav-8-5-35 last"> <a href="http://www.peta.cl/partes-y-piezas/redes/wireless.html"> <span>Wireless</span> </a></li></ul></li><li class="level1 nav-8-6 parent item"> <a href="http://www.peta.cl/partes-y-piezas/software.html"> <span>Software</span> </a><ul class="level1"><li class="level2 nav-8-6-36 first"> <a href="http://www.peta.cl/partes-y-piezas/software/antivirus.html"> <span>Antivirus</span> </a></li><li class="level2 nav-8-6-37"> <a href="http://www.peta.cl/partes-y-piezas/software/sistemas-operativos.html"> <span>Sistemas Operativos</span> </a></li><li class="level2 nav-8-6-38"> <a href="http://www.peta.cl/partes-y-piezas/software/office.html"> <span>Office</span> </a></li><li class="level2 nav-8-6-39 last"> <a href="http://www.peta.cl/partes-y-piezas/software/otros.html"> <span>Otras Apl.</span> </a></li></ul></li><li class="level1 nav-8-7 last item"> <a href="http://www.peta.cl/partes-y-piezas/ups.html"> <span>UPS &amp; Reguladores</span> </a></li></ul></div></div></div></li><li class="level0 nav-9 level-top parent"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks.html" class="level-top"> <span>Equipos</span><span class="caret">&nbsp;</span> </a><div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2"><div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-6col"><ul class="level0"><li class="level1 nav-9-1 first parent item"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos.html"> <span>Fijos</span> </a><ul class="level1"><li class="level2 nav-9-1-1 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/desktops.html"> <span>PC Escritorio</span> </a></li><li class="level2 nav-9-1-2"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/pos.html"> <span>POS</span> </a></li><li class="level2 nav-9-1-3"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/terminal-de-red.html"> <span>Mini-PC &amp; Terminales</span> </a></li><li class="level2 nav-9-1-4 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/fijos/todo-en-uno-aio2.html"> <span>Todo en Uno (AIO)</span> </a></li></ul></li><li class="level1 nav-9-2 parent item"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles.html"> <span>Móviles</span> </a><ul class="level1"><li class="level2 nav-9-2-5 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/e-book.html"> <span>E-Book</span> </a></li><li class="level2 nav-9-2-6"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/notebooks.html"> <span>Notebooks</span> </a></li><li class="level2 nav-9-2-7"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/tablets.html"> <span>Tablets</span> </a></li><li class="level2 nav-9-2-8 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/moviles/ultrabooks.html"> <span>Ultrabooks</span> </a></li></ul></li><li class="level1 nav-9-3 last parent item"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores.html"> <span>Servidores</span> </a><ul class="level1"><li class="level2 nav-9-3-9 first"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores/accesorios.html"> <span>Accesorios</span> </a></li><li class="level2 nav-9-3-10 last"> <a href="http://www.peta.cl/equipos-computadores-tablets-aio-ebooks/servidores/servidores2.html"> <span>Servidores</span> </a></li></ul></li></ul></div></div></div></li><li class="level0 nav-10 level-top last parent"> <a href="http://www.peta.cl/apple.html" class="level-top"> <span>Apple <span class="cat-label cat-label-label2 pin-bottom">Mac !</span></span><span class="caret">&nbsp;</span> </a><div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2"><div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-6col"><ul class="level0"><li class="level1 nav-10-1 first parent item"><div class="nav-block nav-block-level1-top std"> <a class="banner" href="http://www.peta.cl/apple/mac.html/"> <img src="https://www.peta.cl/media/ImagenesVarias/macbookpro.png"  /> </a></div> <a href="http://www.peta.cl/apple/mac.html"> <span>MacBook</span> </a><ul class="level1"><li class="level2 nav-10-1-1 first"> <a href="http://www.peta.cl/apple/mac/macbook-air.html"> <span>MacBook Air</span> </a></li><li class="level2 nav-10-1-2"> <a href="http://www.peta.cl/apple/mac/macbook-pro-retina.html"> <span>MacBook Pro Retina</span> </a></li><li class="level2 nav-10-1-3 last"> <a href="http://www.peta.cl/apple/mac/macbook-pro.html"> <span>MacBook Pro</span> </a></li></ul></li><li class="level1 nav-10-2 parent item"><div class="nav-block nav-block-level1-top std"> <a class="banner" href="http://www.peta.cl/apple/imac.html/"> <img src="https://www.peta.cl/media/ImagenesVarias/imac.png"  /> </a></div> <a href="http://www.peta.cl/apple/imac.html"> <span>iMac &amp; MacPro</span> </a><ul class="level1"><li class="level2 nav-10-2-4 first"> <a href="http://www.peta.cl/apple/imac/mac-mini.html"> <span>Mac mini</span> </a></li><li class="level2 nav-10-2-5"> <a href="http://www.peta.cl/apple/imac/mac-pro.html"> <span>Mac Pro</span> </a></li><li class="level2 nav-10-2-6 last"> <a href="http://www.peta.cl/apple/imac/imac.html"> <span>iMac</span> </a></li></ul></li><li class="level1 nav-10-3 parent item"><div class="nav-block nav-block-level1-top std"> <a class="banner" href="http://www.peta.cl/apple/ipad.html/"> <img src="https://www.peta.cl/media/ImagenesVarias/ipad.png"  /> </a></div> <a href="http://www.peta.cl/apple/ipad.html"> <span>iPad</span> </a><ul class="level1"><li class="level2 nav-10-3-7 first"> <a href="http://www.peta.cl/apple/ipad/ipad-mini.html"> <span>iPad mini</span> </a></li><li class="level2 nav-10-3-8"> <a href="http://www.peta.cl/apple/ipad/ipad-mini-retina.html"> <span>iPad mini (Retina)</span> </a></li><li class="level2 nav-10-3-9"> <a href="http://www.peta.cl/apple/ipad/ipad-2.html"> <span>iPad 2</span> </a></li><li class="level2 nav-10-3-10"> <a href="http://www.peta.cl/apple/ipad/ipad-4-retina.html"> <span>iPad 4 (Retina)</span> </a></li><li class="level2 nav-10-3-11 last"> <a href="http://www.peta.cl/apple/ipad/ipad-air.html"> <span>iPad Air</span> </a></li></ul></li><li class="level1 nav-10-4 parent item"><div class="nav-block nav-block-level1-top std"> <a class="banner" href="http://www.peta.cl/apple/ipod.html/"> <img src="https://www.peta.cl/media/ImagenesVarias/ipod.png"  /> </a></div> <a href="http://www.peta.cl/apple/ipod.html"> <span>iPod</span> </a><ul class="level1"><li class="level2 nav-10-4-12 first"> <a href="http://www.peta.cl/apple/ipod/ipod-classic.html"> <span>iPod classic</span> </a></li><li class="level2 nav-10-4-13"> <a href="http://www.peta.cl/apple/ipod/ipod-touch.html"> <span>iPod touch</span> </a></li><li class="level2 nav-10-4-14"> <a href="http://www.peta.cl/apple/ipod/ipod-nano.html"> <span>iPod nano</span> </a></li><li class="level2 nav-10-4-15 last"> <a href="http://www.peta.cl/apple/ipod/ipod-shuffle.html"> <span>iPod shuffle</span> </a></li></ul></li><li class="level1 nav-10-5 last parent item"> <a href="http://www.peta.cl/apple/accesorios.html"> <span>Accesorios</span> </a><ul class="level1"><li class="level2 nav-10-5-16 first"> <a href="http://www.peta.cl/apple/accesorios/almacenamiento.html"> <span>Almacenamiento</span> </a></li><li class="level2 nav-10-5-17"> <a href="http://www.peta.cl/apple/accesorios/wireless-connectivity.html"> <span>Conectividad</span> </a></li><li class="level2 nav-10-5-18"> <a href="http://www.peta.cl/apple/accesorios/accesorios-ipad.html"> <span>iPad</span> </a></li><li class="level2 nav-10-5-19"> <a href="http://www.peta.cl/apple/accesorios/macbook-accessories.html"> <span>MacBook</span> </a></li><li class="level2 nav-10-5-20"> <a href="http://www.peta.cl/apple/accesorios/monitores.html"> <span>Monitores &amp; Video</span> </a></li><li class="level2 nav-10-5-21"> <a href="http://www.peta.cl/apple/accesorios/miscellaneous-accessories.html"> <span>Otros</span> </a></li><li class="level2 nav-10-5-22"> <a href="http://www.peta.cl/apple/accesorios/perifericos.html"> <span>Periféricos</span> </a></li><li class="level2 nav-10-5-23 last"> <a href="http://www.peta.cl/apple/accesorios/software.html"> <span>Software</span> </a></li></ul></li></ul></div></div></div></li></ul> <script type="text/javascript"> //<![CDATA[

                        
                                        var activateMobileMenu = function()
                    {
                        if (jQuery(window).width() < 800)
                        {
                            jQuery('#mobnav').show();
                            jQuery('.vertnav-top').addClass('mobile');
                            jQuery('#nav').addClass('mobile');
                        }
                        else
                        {
                            jQuery('#nav').removeClass('mobile');
                            jQuery('.vertnav-top').removeClass('mobile');
                            jQuery('#mobnav').hide();
                        }
                    }
                    activateMobileMenu();
                    jQuery(window).resize(activateMobileMenu);
        
                
                                jQuery('#mobnav-trigger').toggle(function() {
                    jQuery('.vertnav-top').addClass('show');
                    jQuery(this).addClass('active');
                }, function() {
                    jQuery('.vertnav-top').removeClass('show');
                    jQuery(this).removeClass('active');
                });
                
        //]]> </script> <script type="text/javascript"> //<![CDATA[

                    jQuery(function($) {
                $("#nav > li").hover(function() {
                    var el = $(this).find(".level0-wrapper");
                    el.hide();
                    el.css("left", "0");
                    el.stop(true, true).delay(200).fadeIn(0, "easeOutCubic");
                }, function() {
                    $(this).find(".level0-wrapper").stop(true, true).delay(200).fadeOut(0, "easeInCubic");
                });
            });
        
            var isTouchDevice = ('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0);
            jQuery(window).on("load", function() {

                if (isTouchDevice)
                {
                    jQuery('#nav a.level-top').click(function(e) {
                        $t = jQuery(this);
                        $parent = $t.parent();
                        if ($parent.hasClass('parent'))
                        {
                            if ( !$t.hasClass('menu-ready'))
                            {                    
                                jQuery('#nav a.level-top').removeClass('menu-ready');
                                $t.addClass('menu-ready');
                                return false;
                            }
                            else
                            {
                                $t.removeClass('menu-ready');
                            }
                        }
                    });
                }

            }); //end: on load

        //]]> </script></div></div></div></div></div>
        <div class="main-container col1-layout">
        	<div class="main-before-top-container"></div><div class="main-top-container">
        		<div class="main-top container clearer"><div class="grid-full"></div></div></div>
        			<div class="main container"><div class="preface grid-full in-col1"></div>
        				<div class="col-main grid-full in-col1"> 
        					<!--ewpagecache:core_messages_begin:03bb8--><!--ewpagecache:core_messages_end-->
        					<div class="account-login clearer">
        						
        						<form action="https://www.peta.cl/customer/account/loginPost/" method="post" id="login-form"> <input name="form_key" type="hidden" value="15060b172e2f9244" /><div class="new-users grid12-6">
        	<div class="content">  
        		<!-- AKI-->   <h2>Webpay</h2>	
	<p style="color: rgb(255, 0, 0); font-weight:bold">Transacci&oacute;n Realizada correctamente </p>
											
												  <img src="http://www.peta.cl/webpay/web-pay-adq.gif" />
                                         
            <table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" align="left" class="list_categoria" ><p style="color: rgb(255, 0, 0);"><strong>Datos de la Transacci&oacute;n</strong></p>  </td>
</tr>
<tr>
  <td width="16%" align="left" class="list_categoria" >Tarjeta : </td>
  <td width="17%" align="left" class="list_categoria" >
  	XXXX - <?php echo $TBK_FINAL_NUMERO_TARJETA;?>    </td>
</tr>
<tr>
  <td class="list_categoria" align="left" >N&ordm; del Pedido: </td>
  <td class="list_categoria" align="left" ><?php echo $TBK_ORDEN_COMPRA;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Nombre del Comercio:</td>
  <td class="list_categoria" align="left" ><?php echo $Comercio;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >URL del comercio:</td>
  <td class="list_categoria" align="left" ><?php echo $url;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Monto:</td>
  <td class="list_categoria" align="left" ><?php   echo " $".number_format($trs_monto, 0, ",", ".")."";	 ?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Moneda: </td>
  <td class="list_categoria" align="left" >Pesos chilenos</td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Fecha transacci&oacute;n:</td>
  <td class="list_categoria" align="left" ><?php echo $trs_fecha_transaccion;?> </td>
 </tr>
 <tr>
  <td class="list_categoria" align="left" >Nombre Comprador:</td>
  <td class="list_categoria" align="left" ><?php echo $comprador;?> </td>
</tr>
<tr>
  <td class="list_categoria" align="left" >C&oacute;digo Autorizaci&oacute;n:</td>
  <td class="list_categoria" align="left" ><?php echo $TBK_CODIGO_AUTORIZACION;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Tipo de transacci&oacute;n:</td>
  <td class="list_categoria" align="left" > Venta</td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Tipo de Cuota:</td>
  <td class="list_categoria" align="left" ><?php echo $tipo_pago_descripcion;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Tipo Pago:</td>
  <td class="list_categoria" align="left" ><?php echo $tipo_pago;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Numero de cuotas:</td>
  <td class="list_categoria" align="left" >  <?php echo $trs_nro_cuotas;?></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Descripci&oacute;n de los Bienes y Servicios </td>
  <td class="list_categoria" align="left" > <a  href="<?php echo $link_pedido;?>" >Detalle pedido</a></td>
</tr>
<tr>
  <td class="list_categoria" align="left" >Revise recctriciones con respecto a  devoluciones y reembolsos.</td>
  <td class="list_categoria" align="left" >
 
Si tienes dudas, por favor escriba a postventa@peta.cl



</td>
						  </tr>
</table>
<?php $numero_pedido=$_GET['id']; ?>
<?php echo "<img src=\"https://junta.cl/pixel.png?amount=" . round($trs_monto) . "&transaction_id=" . $numero_pedido . "\" />" ; ?>
<!-- Google Code for Compra Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012583315;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "KRV3CMXg6QQQk5fr4gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1012583315/?label=KRV3CMXg6QQQk5fr4gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<!-- AKI-->        				</div>
        				<div class="registered-users grid12-6">
        					<div class="content">
        						 
        						<div id="window-overlay" class="window-overlay" style="display:none;"></div>
        						<div id="remember-me-popup" class="remember-me-popup" style="display:none;">
        						<div class="remember-me-popup-body"><div class="remember-me-popup-close-button a-right">
        							</div></div></div> <script type="text/javascript"> //<![CDATA[
    function toggleRememberMepopup(event){
        if($('remember-me-popup')){
            var viewportHeight = document.viewport.getHeight(),
                docHeight      = $$('body')[0].getHeight(),
                height         = docHeight > viewportHeight ? docHeight : viewportHeight;
            $('remember-me-popup').toggle();
            $('window-overlay').setStyle({ height: height + 'px' }).toggle();
        }
        Event.stop(event);
    }

    document.observe("dom:loaded", function() {
        new Insertion.Bottom($$('body')[0], $('window-overlay'));
        new Insertion.Bottom($$('body')[0], $('remember-me-popup'));

        $$('.remember-me-popup-close').each(function(element){
            Event.observe(element, 'click', toggleRememberMepopup);
        })
        $$('#remember-me-box a').each(function(element) {
            Event.observe(element, 'click', toggleRememberMepopup);
        });
    });
//]]> </script>
</div>
</div></form> <script type="text/javascript"> //<![CDATA[
        var dataForm = new VarienForm('login-form', true);
    //]]> </script></div></div><div class="postscript grid-full in-col1"></div></div></div><div class="footer-container"><div class="footer-container2"><div class="footer-top-container section-container"><div class="footer-top footer container stretched"><div class="grid-full"><div class="section clearer links-wrapper-separators mobile-inline-container"></div></div></div></div><div class="footer-top2-container section-container"><div class="footer-top2 footer container stretched"><div class="grid-full"><div class="section clearer mobile-inline-container"><div class="item item-left clearer block_footer_top2_left"><div class="social-links"> <a class="first" href="http://www.twitter.com/petacl" title="Follow us on Twitter"> <span class="icon icon-hover i-twitter-w"></span> </a> <a href="https://www.facebook.com/petacl" title="Join us on Facebook"> <span class="icon icon-hover i-facebook-w"></span> </a> <a href="https://plus.google.com/communities/105597742451781902822" title="Join us on Google Plus"> <span class="icon icon-hover i-googleplus-w"></span> </a></div></div></div></div></div></div><div class="footer-primary-container section-container"><div class="footer-primary footer container show-bg"><div class="grid-full"><div class="section clearer"><div class=" grid12-12"><div class="std"><div class="grid12-2"><div class="collapsible mobile-collapsible"><h6 class="block-title heading">Informaci&oacute;n</h6><div class="block-content"><ul class="disc"><li><a href="http://www.peta.cl/la-empresa">La Empresa</a></li><li><a href="http://www.peta.cl/modelo">Modelo de Negocios</a></li><li><a href="http://www.peta.cl/testimonials">Testimoniales</a></li><li><a href="http://www.peta.cl/comentarios">Comentarios</a></li><li><a href="http://www.peta.cl/pagos">Medios de Pago</a></li></ul></div></div></div><div class="grid12-2"><div class="collapsible mobile-collapsible"><h6 class="block-title heading">Dudas &amp; Soporte</h6><div class="block-content"><ul class="disc"><li><a href="http://www.peta.cl/Preguntas_Frecuentes">Preguntas Frecuentes</a></li><li><a href="http://www.peta.cl/servicios">Entrega &amp; Despacho</a></li><li><a href="http://wiki.peta.cl/">Wiki</a></li><li><a href="http://www.peta.cl/contacts">Cont&aacute;ctenos</a></li></ul></div></div></div><div class="grid12-2"><div class="collapsible mobile-collapsible"><h6 class="block-title heading">Garant&iacute;as</h6><div class="block-content"><ul class="disc"><li><a href="http://www.peta.cl/garantias">Garant&iacute;as</a></li><li><a href="http://www.peta.cl/rma">Devoluciones</a></li><li><a href="http://www.peta.cl/accesorios/otros/open-box.html">Ofertas &amp; Open Box</a></li></ul></div></div></div><div class="grid12-2"><div class="collapsible mobile-collapsible"><h6 class="block-title heading">Varios</h6><div class="block-content"><ul class="disc"><li><a href="http://www.peta.cl/pagos">Medios de Pago</a></li><li><a href="http://www.peta.cl/comprar">Cómo comprar en Peta.cl</a></li><li><a href="http://www.peta.cl/servicios">Plazos de Entrega</a></li><li><a href="http://www.peta.cl/terminos">T&eacute;rminos y Condiciones</a></li><li><a href="http://www.peta.cl/politica">Pol&iacute;tica de Privacidad</a></li></ul></div></div></div><div class="grid12-4"><div class="collapsible mobile-collapsible"><h6 class="block-title heading">Informaci&oacute;n</h6><div class="block-content"><div class="feature indent first feature-icon-hover"> <span class="icon i-location-w force-no-bg-color"></span><p class="no-margin">Peta.cl SpA., Asturias 97<br />Las Condes, Santiago</p></div><div class="feature indent feature-icon-hover"> <span class="icon i-mobile-w force-no-bg-color"></span><p class="no-margin">Fono: +562 2362.7577<br /></p></div><div class="feature indent feature-icon-hover"> <span class="icon i-letter-w force-no-bg-color"></span><p class="no-margin">Soporte: soporte@peta.cl<br>Ventas: ventas@peta.cl</p></div></div></div></div></div></div></div></div></div></div><div class="footer-bottom-container section-container"><div class="footer-bottom footer container"><div class="grid-full"><div class="section clearer mobile-inline-container"><div class="item item-left"><p class="footer-copyright">Copyright © 2013 | Peta.cl SpA -
Todos los derechos reservados <br> Peta.cl SpA es una empresa del grupo Kepler SA<br> Dirección oficina: Asturias 97, Santiago de Chile | Teléfono: (+562) 2362.7577 | email: ventas@peta.cl<br><br><div style="width:98px"><div style="color:#777; font-size:10px; font-family:Arial,sans-serif">Opiniones de</div> <a href="http://es.testfreaksdata.com"> <img src="https://www.peta.cl/media/testfreaks_98x15.png" width="98" height="15" alt="TestFreaks" title="TestFreaks" style="border:none" /> </a></div><br> <strong>Sitio web desarrollado por <a href="http://www.moly.cl">www.moly.cl</a></strong></p></div><div class="item item-right block_footer_payment"><ul><li><a href="http://www.peta.cl/pagos"><img src="https://www.peta.cl/media/extendware/ewminify/media/skin/d6/8/payment.png" alt="" /></a></li></ul></div></div></div></div></div> <a href="#top" id="scroll-to-top">To top</a></div></div> <script type="text/javascript"> //<![CDATA[

			var gridItemsEqualHeightApplied = false;
	function setGridItemsEqualHeight($)
	{
		var $list = $('.category-products-grid');
		var $listItems = $list.children();

		var centered = $list.hasClass('centered');
		var gridItemMaxHeight = 0;
		$listItems.each(function() {
			
			$(this).css("height", "auto"); 			var $object = $(this).find('.actions');

						if (centered)
			{
				var objectWidth = $object.width();
				var availableWidth = $(this).width();
				var space = availableWidth - objectWidth;
				var leftOffset = space / 2;
				$object.css("padding-left", leftOffset + "px"); 			}

						var bottomOffset = parseInt($(this).css("padding-top"));
			if (centered) bottomOffset += 10;
			$object.css("bottom", bottomOffset + "px");

						if ($object.is(":visible"))
			{
								var objectHeight = $object.height();
				$(this).css("padding-bottom", (objectHeight + bottomOffset) + "px");
			}

						
			gridItemMaxHeight = Math.max(gridItemMaxHeight, $(this).height());
		});

		//Apply max height
		$listItems.css("height", gridItemMaxHeight + "px");
		gridItemsEqualHeightApplied = true;

	}
	


	jQuery(function($) {

				$('.collapsible').each(function(index) {
			$(this).prepend('<span class="opener">&nbsp;</span>');
			if ($(this).hasClass('active'))
			{
				$(this).children('.block-content').css('display', 'block');
			}
			else
			{
				$(this).children('.block-content').css('display', 'none');
			}			
		});
				$('.collapsible .opener').click(function() {
			
			var parent = $(this).parent();
			if (parent.hasClass('active'))
			{
				$(this).siblings('.block-content').stop(true).slideUp(300, "easeOutCubic");
				parent.removeClass('active');
			}
			else
			{
				$(this).siblings('.block-content').stop(true).slideDown(300, "easeOutCubic");
				parent.addClass('active');
			}
			
		});
		
		
				var ddOpenTimeout;
		var dMenuPosTimeout;
		var DD_DELAY_IN = 200;
		var DD_DELAY_OUT = 0;
		var DD_ANIMATION_IN = 0;
		var DD_ANIMATION_OUT = 0;
		$(".clickable-dropdown > .dropdown-toggle").click(function() {
			$(this).parent().addClass('open');
			$(this).parent().trigger('mouseenter');
		});
		$(".dropdown").hover(function() {
			
			
			var ddToggle = $(this).children('.dropdown-toggle');
			var ddMenu = $(this).children('.dropdown-menu');
			var ddWrapper = ddMenu.parent(); 			
						ddMenu.css("left", "");
			ddMenu.css("right", "");
			
						if ($(this).hasClass('clickable-dropdown'))
			{
								if ($(this).hasClass('open'))
				{
					$(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
				}
			}
			else
			{
								clearTimeout(ddOpenTimeout);
				ddOpenTimeout = setTimeout(function() {
					
					ddWrapper.addClass('open');
					
				}, DD_DELAY_IN);
				
				//$(this).addClass('open');
				$(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
			}
			
						clearTimeout(dMenuPosTimeout);
			dMenuPosTimeout = setTimeout(function() {

				if (ddMenu.offset().left < 0)
				{
					var space = ddWrapper.offset().left; 					ddMenu.css("left", (-1)*space);
					ddMenu.css("right", "auto");
				}
			
			}, DD_DELAY_IN);
			
		}, function() {
			var ddMenu = $(this).children('.dropdown-menu');
			clearTimeout(ddOpenTimeout); 			ddMenu.stop(true, true).delay(DD_DELAY_OUT).fadeOut(DD_ANIMATION_OUT, "easeInCubic");
			if (ddMenu.is(":hidden"))
			{
				ddMenu.hide();
			}
			$(this).removeClass('open');
		});
		
		
		
							$(".main").addClass("show-bg");
				
		
		
				var windowScroll_t;
		$(window).scroll(function(){
			
			clearTimeout(windowScroll_t);
			windowScroll_t = setTimeout(function() {
										
				if ($(this).scrollTop() > 100)
				{
					$('#scroll-to-top').fadeIn();
				}
				else
				{
					$('#scroll-to-top').fadeOut();
				}
			
			}, 500);
			
		});
		
		$('#scroll-to-top').click(function(){
			$("html, body").animate({scrollTop: 0}, 600, "easeOutCubic");
			return false;
		});
		
		
		
				
			var startHeight;
			var bpad;
			$('.category-products-grid').on('mouseenter', '.item', function() {

														if ($(window).width() >= 320)
					{
				
											if (gridItemsEqualHeightApplied === false)
						{
							return false;
						}
					
					startHeight = $(this).height();
					$(this).css("height", "auto"); //Release height
					$(this).find(".display-onhover").fadeIn(400, "easeOutCubic"); //Show elements visible on hover
					var h2 = $(this).height();
					
										////////////////////////////////////////////////////////////////
					var addtocartHeight = 0;
					var addtolinksHeight = 0;
					
										
											var addtolinksEl = $(this).find('.add-to-links');
						if (addtolinksEl.hasClass("addto-onimage") == false)
							addtolinksHeight = addtolinksEl.innerHeight(); //.height();
										
											var h3 = h2 + addtocartHeight + addtolinksHeight;
						var diff = 0;
						if (h3 < startHeight)
						{
							$(this).height(startHeight);
						}
						else
						{
							$(this).height(h3); 							diff = h3 - startHeight;
						}
										////////////////////////////////////////////////////////////////

					$(this).css("margin-bottom", "-" + diff + "px"); 
									} 								
			}).on('mouseleave', '.item', function() {

													if ($(window).width() >= 320)
					{
				
					//Clean up
					$(this).find(".display-onhover").stop(true).hide();
					$(this).css("margin-bottom", "");

																$(this).height(startHeight);
					
									} 								
			});
		
		


				$('.products-grid, .products-list').on('mouseenter', '.item', function() {
			$(this).find(".alt-img").fadeIn(400, "easeOutCubic");
		}).on('mouseleave', '.item', function() {
			$(this).find(".alt-img").stop(true).fadeOut(400, "easeOutCubic");
		});



				$('.fade-on-hover').on('mouseenter', function() {
			$(this).animate({opacity: 0.75}, 300, 'easeInOutCubic');
		}).on('mouseleave', function() {
			$(this).stop(true).animate({opacity: 1}, 300, 'easeInOutCubic');
		});



						var winWidth = $(window).width();
		var winHeight = $(window).height();
		$(window).resize(
			$.debounce(50, onEventResize)
		); //end: resize

				function onEventResize() {

						var winNewWidth = $(window).width();
			var winNewHeight = $(window).height();
			if (winWidth != winNewWidth || winHeight != winNewHeight)
			{
				afterResize(); 			}
			//Update window size variables
			winWidth = winNewWidth;
			winHeight = winNewHeight;

		} //end: onEventResize

				function afterResize() {

						$(document).trigger("themeResize");

										setGridItemsEqualHeight($);
									
						$('.itemslider').each(function(index) {
				var flex = $(this).data('flexslider');
				if (flex != null)
				{
					flex.flexAnimate(0);
					flex.resize();
				}
			});
							
						var slideshow = $('.the-slideshow').data('flexslider');
			if (slideshow != null)
			{
				slideshow.resize();
			}

		} //end: afterResize



	}); /* end: jQuery(){...} */
	
	

	jQuery(window).load(function(){
		
							setGridItemsEqualHeight(jQuery);
		
	}); /* end: jQuery(window).load(){...} */

	//]]> </script></div></div></div></body></html>
