<!DOCTYPE html>
<html>
  <head>
    <title>
      Facebook Hackathon
    </title>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <script src="https://code.jquery.com/jquery-1.11.0.min.js">
    </script>
    <script src="questions.js">
    </script>
  </head>
  <body>
    <div id="fb-root"></div>
    <script>
      $(document).on('click', '#login', function(e) {
        e.preventDefault();
        FB.login(function(){}, {scope:'user_hometown,friends_hometown'});
      });

      window.fbAsyncInit = function() {
        FB.init({
          appId      : '649041091809661',
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });

        FB.Event.subscribe('auth.authResponseChange', checkLoginStatus);
      };

      // Load the SDK asynchronously
      (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
      }(document));

      function checkLoginStatus(response) {
        if (response.status === 'connected') {
          console.log('connected');
          // The response object is returned with a status field that lets the app know the current
          // login status of the person. In this case, we're handling the situation where they 
          // have logged in to the app.
          FB.api('/me/permissions', function (response) {
            if(response.data[0]['user_hometown'] == 1) {
              console.log('Permissions are granted.');
              $('#content').css('display', 'block');
              $('#login').css('display', 'none');
            }
          });

        } else if (response.status === 'not_authorized') {
          console.log('not_auth');
          // In this case, the person is logged into Facebook, but not into the app, so we call
          // FB.login() to prompt them to do so. 
          // In real-life usage, you wouldn't want to immediately prompt someone to login 
          // like this, for two reasons:
          // (1) JavaScript created popup windows are blocked by most browsers unless they 
          // result from direct interaction from people using the app (such as a mouse click)
          // (2) it is a bad experience to be continually prompted to login upon page load.
        } else {
          console.log('else');
          // In this case, the person is not logged into Facebook, so we call the login() 
          // function to prompt them to do so. Note that at this stage there is no indication
          // of whether they are logged into the app. If they aren't then they'll see the Login
          // dialog right after they log in to Facebook. 
          // The same caveats as above apply to the FB.login() call here.
        }
      }

    </script>

    <a href="#" id="login">
      Login
    </a>


    <div id="content" style="display:none;">
      Here's the main stuff for the page!!
    </div>
  </body>
</html>
