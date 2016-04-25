<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
	<title>{TITLE}</title>
	<link type="text/css" rel="stylesheet" href="{TPL_WEB_PATH}css/template.css" />
	<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.js" charset="utf-8"> </script>
	<script type="text/javascript" src="/lib/XHRConnection.js" charset="utf-8"> </script>
	<script type="text/javascript" src="{TPL_WEB_PATH}js/xmlHTTPrequest.js" charset="utf-8"> </script>
	<script type="text/javascript" src="{TPL_WEB_PATH}js/utils.js" charset="utf-8"> </script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-32210485-3', 'jops-dev.com');
		ga('send', 'pageview');
	</script>
	<script type="text/javascript">
	/* <![CDATA[ */
		(function() {
			var s = document.createElement('script');
			var t = document.getElementsByTagName('script')[0];

			s.type = 'text/javascript';
			s.async = true;
			s.src = '//api.flattr.com/js/0.6/load.js?'+
					'mode=auto&uid=dst17&language=fr_FR&category=text';

			t.parentNode.insertBefore(s, t);
		})();

		window.onload = function() {
			FlattrLoader.render({
				'uid': 'dst17',
				'url': 'http://cdm2014.jops-dev.com',
				'title': 'Pronostics de la coupe du monde 2014',
				'description': 'Aidez nous à payer l\'hébergement du site !'
			}, 'flattr_button', 'replace');
		};
	/* ]]> */
	</script>
	</script>
</head>
<body>