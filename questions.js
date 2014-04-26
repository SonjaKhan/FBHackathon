$(window).load(function() {
  var questionNumber = 1;
  // Click handler for the next question button
  $(document).on('click', '#next_question_button', fetchNextQuestion);
  $(document).on('click', '#begin', beginQuestions);

  // Sends off an AJAX request to server to fetch the info
  // for the next question
  function fetchNextQuestion(e) {
    e.preventDefault();

    $.ajax({
      url: 'sample_question.php',
      dataType: 'json',
      success: displayNextQuestion
    });
  }

  function beginQuestions(e) {
    $('#begin').css('display', 'none');
    $('#content').css('display', 'block');
    fetchNextQuestion(e);
  }

  function displayNextQuestion(data) {
    $("#question span").html("Question #" + questionNumber);
    $("#question p").html(data.question_text);
    $("#answers ul li label").each(function(index) {
      $(this).html(
        $("<input>").attr("type", "radio").attr("name", "selection").attr("value", index)
      ).append(data.answers[index]);
    });
  }
});
