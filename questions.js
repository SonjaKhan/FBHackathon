$(window).load(function() {
  var questionNumber = 1;
  score = 0;

  $(document).on('click', '#begin a', beginQuestions);
  $(document).on('click', 'label:not(.unselectable)', checkQuestion);

  // Check this answer to see if it's correct
  function checkQuestion(e) {
    e.preventDefault();
    $(this).addClass("selected");
	$("label").addClass("unselectable");
	console.log("called check");
    gotIt = false;
    if ($("label.selected input").attr("value") == 0) {
      // They got the question correct! Hurah!
      score++;
      gotIt = true;
      $(".progressblock:nth-child(" + (questionNumber - 1) + ")").css("background", "linear-gradient(#C0FFC0, #A0FFA0)");
    } else {
      // Incorrect answer
      $("label.selected").addClass("incorrect");
      $(".progressblock:nth-child(" + (questionNumber - 1) + ")").css("background", "linear-gradient(#FFC0C0, #FFA0A0)");
    }
    // Mark the correct answer
    $("label input[value=0]").parent().addClass("correct");
	window.setTimeout((questionNumber <= 15) ? fetchNextQuestion : displaySummary, (gotIt) ? 1500 : 2500);
  }
  
  function displaySummary() {
	$("#answers").html("");
	$("#question").html("");
    $("#question").append($("<span>").html("Summary"));
	$("#question").append($("<div>").attr("id", "finalPercent").html("Total: " + Math.floor(score / (questionNumber - 1) * 100) + "%"));
	$("#question").append($("<p>").html("Of 15 questions about your friends, you got " + score + "!"));
	$("#question").append($("<a>").attr("id", "postScores").attr("href","#").html("Click here to share your score"));
	$("#question").append($("<a>").attr("href","#").html("Click here to play again"));
  }

  // Sends off an AJAX request to server to fetch the info
  // for the next question
  function fetchNextQuestion() {

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
      url: 'question.php',
      dataType: 'json',
      success: displayNextQuestion
    });
  }

  function beginQuestions(e) {
    e.preventDefault();
    $('#prompt').css('display', 'none');
    $('#content').css('display', 'block');
    fetchNextQuestion();
  }

  function displayNextQuestion(data) {
    $('#loader-div').remove();

    question = data.question;
    $("#question span").html("Question #" + questionNumber);

    if(question.type == 'status') {
      question.question_text = 'Who posted <blockquote>' + question.question_text.slice(11) + '</blockquote>';
    }

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
