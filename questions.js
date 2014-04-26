$(window).load(function() {
  var questionNumber = 1;
  // Click handler for the next question button
  $(document).on('click', '#next_question_button', fetchNextQuestion);
  $(document).on('click', '#begin a', beginQuestions);

  // Sends off an AJAX request to server to fetch the info
  // for the next question
  function fetchNextQuestion(e) {
    e.preventDefault();

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
    question = data.question;
    $("#question span").html("Question #" + questionNumber);
    $("#question p").html(question.question_text);

    var indices = [];
    for(var i=0;i<question.answers.names.length;i++) {
      indices.push(i);
    }

    $("#answers ul li label").each(function(index) {
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
