<DOCTYPE HTML>
<html lang="de-x-mtfrom-en">
  <head>
    <title>Translate API Example</title>
    <link rel="alternate machine-translated-from" hreflang="en" href="http://en.example.com/abeille.html">

  </head>
  <body>
    <div id="sourceText">
        <p><img height="150" width="150" sizes="(max-width: 150px) 100vw, 150px" srcset="http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-150x150.jpg 150w, http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-300x300.jpg 300w, http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy.jpg 1000w" alt="Scott McNealy" src="http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-150x150.jpg" class="alignleft size-thumbnail wp-image-7028">Scott McNealy (@ScottMcNealy)<br>
            Co-Founder, Chairman of the Board, and CEO, Sun Microsystems, Inc.<br>
            Co-Founder, Board Member, Curriki<br>
            Co-Founder, Chairman of the Board, Wayin<br>
            Board Member, San Jose Sharks Sports and Entertainment</p>        
        <p>Scott McNealy, Co-Founded Sun Microsystems in 1982 and served as CEO and Chairman of the Board for 22 years during which he piloted the company from startup to legendary Silicon Valley giant in computing infrastructure, network computing, and open source software. Under his watch, Sun Microsystems employed approximately 235,000 worldwide. Sun was sold to Oracle in 2010 for $7.4 billion.</p>
        <p>McNealy is committed to innovation in technology and education and is an outspoken advocate for personal liberty and responsibility, small government, and free-market competition. He is heavily involved in advisory roles for companies that range from startup stage to large corporations. McNealy believes in the philosophy that “Without choice, you have no innovation. Without innovation, you have nothing.”</p>
    </div>
    <div id="translation"></div>
    <script>
      function translateText(response) {
        document.getElementById("translation").innerHTML += "<br>" + response.data.translations[0].translatedText;
      }
    </script>
    <script>
      var newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      var sourceText = escape(document.getElementById("sourceText").innerHTML);
      // WARNING: Your API key will be visible in the page source.
      // To prevent misuse, restrict your key to designated domains or use a
      // proxy to hide your key.
      var source = 'https://www.googleapis.com/language/translate/v2?key=AIzaSyCM4dkAV04CcScsRohPnJuCWvXBpMns3gE&source=en&target=ja&callback=translateText&q=' + sourceText;
      newScript.src = source;

      document.getElementsByTagName('head')[0].appendChild(newScript);
    </script>
  </body>
</html>