<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Hack-a-thon</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <script src="//connect.facebook.net/en_US/all.js">
    </script>
    <script src="https://code.jquery.com/jquery-1.11.0.min.js">
    </script>
    <script src="questions.js">
    </script>
    <script src="fb_login.js">
    </script>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '{your-app-id}',
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        });

        FB.Event.subscribe('auth.authResponseChange', function(response) {
          if (response.status === 'connected') {
            testAPI();
          } else if (response.status === 'not_authorized') {
            FB.login();
          } else {
            FB.login();
          }
        });
      };

      (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
      }(document));

      function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
          console.log('Good to see you, ' + response.name + '.');
        });
      }
    </script>

  </head>

  <body>
    <div id="content">
    <h1>
      Welcome to Facebook Flashcards
    </h1>

    <fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>

    <input type="submit" value="Next Question" id="next_question_button" />
    </div>
  </body>
</html>
