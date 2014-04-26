<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Final</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css' />
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="style.js"></script>
    <script src="questions.js"></script>
    <script src="spin.min.js"></script>
    <script src="jquery.spin.js"></script>
  </head>
  <body>
    <div id="fb-root"></div>
    <script>
      $(document).on('click', '#login a', function(e) {
        e.preventDefault();
        FB.login(function(){}, {scope:'user_hometown,friends_hometown,user_birthday,friends_birthday,user_status,friends_status,user_interests,friends_interests'});
      });

      window.fbAsyncInit = function() {
        FB.init({
          appId      : '649041091809661',
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });

        FB.Event.subscribe('auth.authResponseChange', checkLoginStatus);
        $(document).on('click', '#postScores', getPostPermissions);
      };

      // Load the SDK asynchronously
      (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
      }(document));

      function getPostPermissions(e) {
        console.log('getPostPermissions');
        e.preventDefault();
        FB.Event.unsubscribe('auth.authResponseChange', checkLoginStatus);
        FB.Event.subscribe('auth.authResponseChange', checkPostPermissions);

        FB.login(function(){}, {scope: 'publish_actions'});
      }

      function checkPostPermissions(response) {
        console.log('checkingPostPermissions');
        if (response.status === 'connected') {
          console.log('connected');
          FB.api('/me/permissions', function (response) {
            var perms = response.data[0];
            if(perms['publish_actions']) {
              console.log('Publish permissions are granted.');
              $.ajax({
                url: 'post_score.php',
                data: {
                  score: score
                },
                dataType: 'html',
                success: scorePosted
              });
            }
          });

        } else if (response.status === 'not_authorized') {
          console.log('not_auth');
        } else {
          console.log('else');
        }

        FB.Event.unsubscribe('auth.authResponseChange', checkPostPermissions);
        FB.Event.subscribe('auth.authResponseChange', checkLoginStatus);
      }

      function scorePosted(data) {
        $('#postScores').remove();
      }

      function checkLoginStatus(response) {
        if (response.status === 'connected') {
          console.log('connected');
          // The response object is returned with a status field that lets the app know the current
          // login status of the person. In this case, we're handling the situation where they 
          // have logged in to the app.
          FB.api('/me/permissions', function (response) {
            var perms = response.data[0];
            if(perms['user_interests'] == 1 && perms['user_hometown'] == 1 && perms['friends_hometown'] == 1 && perms['user_birthday'] == 1 && perms['friends_birthday'] == 1 && perms['user_status'] == 1 && perms['friends_status'] == 1 && perms['friends_interests'] == 1) {
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
      Facebook Final
    </h1>
    <div id="prompt">
      <div id="login">
        <a href="#">
          Please Login
        </a>
        <p>
          Welcome! To take the Facebook Final, you'll need to log in to your Facebook account
          so we can grab information about your friends. Please click the link above to continue.
        </p>
      </div>
      <div id="begin">
        <a href="#">
          Begin!
        </a>
        <p>
          The Facebook Final is about to begin. This is a closed book, open face exam. How well
		  do you know your friends?
        </p>
      </div>
    </div>
    <div id="content">
      <div id="question">
        <span>Question #0:</span>
        <p>Do you know the team behind this app?</p>
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
        </div>
      </form>
      <div id="progress">
        <div id="progressbar">
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
          <div class="progressblock"></div>
        </div>
        <div id="bottom">
        </div>
    </div>
  </body>
</html>
