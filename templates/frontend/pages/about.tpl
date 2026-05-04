
<!doctype html>
<html lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{title|strip_tags value=$pageTitleTranslated}</title>
	{load_header context="frontend"}
	{load_stylesheet context="frontend"}
</head>

<body>
  <h1>
    {translate key="about.aboutContext"}
  </h1>
  {$currentContext->getLocalizedData('about')}
  {load_script context="frontend"}
</html>