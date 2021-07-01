<?php





    echo '
    <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
    <meta name="description" content="My Profile">
    <meta name="author" content="YK Social Network">
  
    <link rel="stylesheet" href="css/headerstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>
            
                    <div id = "TagPeopleHeader">
                        Tag People

                        <div id = "BackOption" onclick = "tagPeopleGoBack()">
                                            <img src = "images/left-arrow.png" />
                        </div>
                    </div>

                    <div id = "TagPeopleSearch">
                            <div id = "MyInput">
                                    <input type="text" onfocus = "this.value=''" onfocusout = "this.value=\'Search for friends\'" id="TagSearch" name="TagSearch" value = "Search for friends">
                            
                                    <img src="images/magnifyingglass.png" />    
                            </div>
                            

                            <a onclick = "tagPeopleGoBack()">Done</a>
                    </div>
                    <br />
                    <div id = "TagPeopleSuggestionsHeader">
                            SUGGESTIONS
                    </div>

    
    ';





echo '</body></html>';

?>