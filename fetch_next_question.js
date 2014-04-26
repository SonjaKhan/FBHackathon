$(window).load(function() {
  // Click handler for the next question button
  $(document).on('click', '.next', fetchNextQuestion);

  // Sends off an AJAX request to server to fetch the info
  // for the next question
  function fetchNextQuestion(e) {
    e.preventDefault();
    alert('what');
  }
});
