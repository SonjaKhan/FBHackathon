$(window).load(function() {
  var questionNumber = 1;
  var score = 0;
  // Click handler for the next question button
  $(document).on('click', '#next_question_button', fetchNextQuestion);
  $(document).on('click', '#begin a', beginQuestions);
  $(document).on('click', 'input[type=submit], label.selected', checkQuestion);
  
  // Check this answer to see if it's correct
  function checkQuestion(e) {
    e.preventDefault();
    $("input[type=submit]").css("display", "none");
  
    if ($("label.selected input").attr("value") == 0) {
    // They got the question correct! Hurah!
    score++;
  } else {
    // Incorrect answer
    console.log($("label.selected input").attr("value"));
    $("label.selected").addClass("incorrect");
  }
  // Mark the correct answer
  $("label input[value=0]").parent().addClass("correct");
  
  $("#next_question_button").css("display", "block");
  }

  // Sends off an AJAX request to server to fetch the info
  // for the next question
  function fetchNextQuestion(e) {
    e.preventDefault();

    var loaderDiv = $(document.createElement('div'));
    loaderDiv.css('position', 'fixed');
    loaderDiv.css('top', '0px');
    loaderDiv.css('left', '0px');
    loaderDiv.css('width', '100%');
    loaderDiv.css('height', '100%');
    loaderDiv.css('background', 'rgba(255, 255, 255, 0.7');

    loaderDiv.attr('id', 'loader-div');

    $('body').append(loaderDiv);

    var opts = {
      lines: 13, // The number of lines to draw
      length: 20, // The length of each line
      width: 10, // The line thickness
      radius: 30, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: '#000', // #rgb or #rrggbb or array of colors
      speed: 1, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: '50%', // Top position relative to parent
      left: '50%' // Left position relative to parent
    };

    new Spinner(opts).spin(loaderDiv[0]);

    $.ajax({
      url: 'temp.php',
      dataType: 'json',
      success: displayNextQuestion
    });
  }

  function beginQuestions(e) {
    $('#prompt').css('display', 'none');
    $('#content').css('display', 'block');
    fetchNextQuestion(e);
  }

  function displayNextQuestion(data) {
    $('#loader-div').remove();

    question = data.question;
  $("input[type=submit]").css("display", "block");
  $("#next_question_button").css("display", "none");
    $("#question span").html("Question #" + questionNumber);
    $("#question p").html(question.question_text);

    var indices = [];
    for(var i=0;i<question.answers.names.length;i++) {
      indices.push(i);
    }

    $("#answers ul li label").each(function(index) {
    $(this).removeClass();
      if(question.answers.uids) {
        var rand = Math.floor(Math.random() * indices.length);
        var newIndex = indices[rand];
        indices.splice(rand, 1);
        $(this).html($("<img>").attr("src", "https://graph.facebook.com/" + question.answers.uids[newIndex] + "/picture?width=100&height=100").attr("alt", "Profile picture of " + question.answers.names[newIndex]));
        $(this).append($("<input>").attr("type", "radio").attr("name", "selection").attr("value", newIndex));// May want to store something different later, other than index
        $(this).append($("<span>").html(question.answers.names[newIndex]));
      }
    });

    questionNumber++;
  }
});
