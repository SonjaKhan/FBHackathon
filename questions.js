$(window).load(function() {
  // Click handler for the next question button
  $(document).on('click', '#next_question_button', fetchNextQuestion);

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

  function displayNextQuestion(data) {
    alert('got response!');
    alert(data);
  }
});
