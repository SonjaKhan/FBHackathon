<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Hack-a-thon</title>
    <link href="style.css" rel="stylesheet" type="text/css" media="all" />
    <script src="https://code.jquery.com/jquery-1.11.0.min.js">
    </script>
    <script src="fetch_next_question.js">
    </script>
  </head>

  <body>
    <div id="content">
      <div id="question">
        <span>Question #X:</span>
        <p>Which friend has been to <strong>Paris</strong>?</p>
      </div>
      <div id="answers">
        <ul>
          <li><label><input type="radio" name="selection" value="0" />Roee Avnon</label></li>
          <li><label><input type="radio" name="selection" value="1" />Sonja Khan</label></li>
          <li><label><input type="radio" name="selection" value="2" />Nicholas Reiter</label></li>
          <li><label><input type="radio" name="selection" value="3" />Colin Miller</label></li>
        </ul>

      <input type="submit" value="Next Question" id="next_question_button" />
      </div>
    </div>
  </body>
</html>
