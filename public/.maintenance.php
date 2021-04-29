<?php

header('HTTP/1.1 503 Service Unavailable');

?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="robots" content="noindex">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1">

<style>
body { color: #333; background: white; font: normal 20px/1.5 sans-serif }
h1 { font-size: 2em; margin: 0 }
p { margin: 0 }
.msg { max-width: 500px; margin: 10% auto }
</style>

<title>Site is temporarily down for maintenance</title>

<div class="msg">
            <h1>Stránka je dočasně mimo provoz kvůli údržbě.</h1>
            <p>Prosím zkuste to znovu později.</p>
            <p>Omlouváme se za za nepříjemnosti.</p>
            <p>Sledujte náš <a href="https://www.facebook.com/podebradskamladez/" target="_blank">facebook</a></p>
            <p><a href="mailto:sompodebradskehosenioratu@gmail.com">sompodebradskehosenioratu@gmail.com</a></p>
</div>

<script type="text/javascript">
	function ping() {
		var httpRequest = new XMLHttpRequest();
		if (httpRequest) {
			httpRequest.onreadystatechange = function() {
				if (httpRequest.readyState === XMLHttpRequest.DONE) {
					if (httpRequest.status === 200) {
						location.reload();
					} else {
						setTimeout(function(){
							ping();
						}, 1000);
					}
				}
			};
			httpRequest.open('GET', '');
			httpRequest.send();
		}
	}
	ping();
</script>

<?php

exit;
