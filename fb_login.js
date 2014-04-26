$(window).load(function() {
  FB.init({
    appId      : '649041091809661',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // User is logged in
      loggedIn();
    } else if (response.status === 'not_authorized') {
      // Want to handle this differently later
      FB.login();
    } else {
      FB.login();
    }
  });
};

// Here we run a very simple test of the Graph API after login is successful. 
// This testAPI() function is only called in those cases. 
function loggedIn() {
  console.log('Welcome!  Fetching your information.... ');
  FB.api('/me', function(response) {
    console.log('Good to see you, ' + response.name + '.');
  });
}
