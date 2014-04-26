<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Hack-a-thon</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css' />
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="style.js"></script>
    <script src="questions.js"></script>
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
              $('#begin').css('display', 'block');
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

    <h1>
      Super Awesome App
    </h1>

    <a href="#" id="login">
      Please Login
    </a>

    <a href="#" id="begin">
      Begin!
    </a>

    <div id="content">
      <div id="question">
        <span>Question #1:</span>
        <p>Which friend has been to <strong>Paris</strong>?</p>
      </div>
      <form action="/">
        <div id="answers">
          <ul class="layout-columns">
            <li>
        <label>
          <img src="https://graph.facebook.com/roee.avnon/picture?width=100&height=100" alt="image" />
          <input type="radio" name="selection" value="0" />
          <span>Roee Avnon</span>
        </label>
      </li>
            <li>
        <label>
          <img src="https://graph.facebook.com/sonja.khan.18/picture?width=100&height=100" alt="image" />
          <input type="radio" name="selection" value="1" />
          <span>Sonja Khan</span>
        </label>
      </li>
            <li>
        <label>
          <img src="https://graph.facebook.com/scriptreiter/picture?width=100&height=100" alt="image" />
          <input type="radio" name="selection" value="2" />
          <span>Nicholas Reiter</span>
        </label>
      </li>
            <li>
        <label>
          <img src="https://graph.facebook.com/colinjmiller93/picture?width=100&height=100" alt="image" />
          <input type="radio" name="selection" value="3" />
          <span>Colin Miller</span>
        </label>
      </li>
          </ul>
          <input type="submit" />
          <input id="next_question_button" type="button" value="Next Question" />
        </div>
      </form>
    </div>
  </body>
</html>
