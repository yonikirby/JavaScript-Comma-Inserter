<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");

$_SESSION['friendid'] = null;

$myID = $_SESSION["userid"];

$oldestPostTimestampSoFar = date("Y-m-d H:i:s");   

$_SESSION['oldestPostTimestampSoFar'] = $oldestPostTimestampSoFar;

include('header.php');
?>

<!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
  
    <link rel="stylesheet" href="css/newsfeedstyle.css">
    <link rel="stylesheet" href="css/myprofilestyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>

<script>
  $(document).ready(function() {
      $( "#HeaderContainer" ).css( "float", "none" );

      window.scrollTo(0, 0);

        
  });

  


</script>

<div id = "OpaqueDiv">

</div>


<div id = "NewsfeedContainer">
        <div id = "ShareToFriendsWall">
            <div id = "TopDiv">
                    Share on a Friend's Profile

                    <div id = "ShareToFriendsWallCancelButton">
                        <img src = "images/cancel.png">
                    </div>
            </div>
            <div id = "ShareToFriendsWallContentDiv">

            </div>
        </div>



        <div id = "NewsfeedCreatePost">
            <div id = "NewsfeedCreatePostInner">
                <div id = "NewsfeedCreatePostTop">
                    <img id = "NewsfeedCreatePostProfileSrc" src = "<?php echo $_SESSION['profile_src']; ?>" />
                    

                    <div id = "NewsfeedWhatsOnYourMind" onclick = "showCreatePost('<?php echo $myID; ?>', '<?php echo $_SESSION["firstname"]; ?>')">
                        &nbsp;&nbsp;What's on your mind, <?php echo $_SESSION["firstname"]; ?>?
                    </div>
                    
                    
                </div>
                
                <div id = "NewsfeedCreatePostAddPhotoDiv" onclick = "showCreatePost(); addPhoto();">
                        <img id = "NewsfeedCreatePostAddPhoto" src = "images/addphoto.png" />
                        &nbsp; Photo
                </div>
            </div>
        </div>


        <?php

        echo '<script>var myFullName = "'.$_SESSION["firstname"].' '.$_SESSION["lastname"].'";</script>';
        

    $sql = "SELECT * FROM friends WHERE status = '1' AND (user_two_id = '$myID' OR user_one_id = '$myID')";

    if (!$result1 = mysqli_query($con,$sql)){
      echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
      
    }

    $IDsToQuery = (array) null;

              
                      		
    while ($row = mysqli_fetch_array($result1)) {
  
        $UserOneID = $row['user_one_id'];
        $UserTwoID = $row['user_two_id'];
        
        if ($myID == $UserOneID){
            array_push($IDsToQuery,$UserTwoID);
        }
        else {
            array_push($IDsToQuery,$UserOneID);
          
        }

        
    }
    echo '
        <script>var friendCount = '.count($IDsToQuery).';</script>
    ';

    $ImplodedFriendsArray = implode(',', $IDsToQuery);

    echo '
                        <script>
                            var myFriendsIDs = "'.$ImplodedFriendsArray.'";

                        </script>
                    ';



    if (count($IDsToQuery) > 0){     
                    $sql = "SELECT DISTINCT postid FROM notifications WHERE (notificationrecipient in (" . $ImplodedFriendsArray . ") 
                    OR notificationrecipient = '$myID') AND posteeid != '-1'
                    ORDER BY ID DESC
                    ";
        
                    if (!$result2 = mysqli_query($con,$sql)){
                        echo '<script type="text/javascript">alert("num4 first query '.mysqli_error($con).'");</script>';
                                    
                    }


                    $TaggedInPostsToQuery = (array) null;

                    
                      		
                    while ($row = mysqli_fetch_array($result2)) {
                  
                        $id = $row['postid'];
                        if ($id > 0){
                            array_push($TaggedInPostsToQuery,$id);
                        }
                    }

                    $ImplodedTaggedInPostsToQueryArray = implode(',', $TaggedInPostsToQuery);

                    echo '
                        <script>
                            var myFriendsTaggedPostIDs = "'.$ImplodedTaggedInPostsToQueryArray.'";

                            

                        </script>
                    ';
                    
                    $sql = "select * from posts where (posterid in (" . $ImplodedFriendsArray . ")
                            OR posteeid in (" . $ImplodedFriendsArray . ")
                            OR id in (" . $ImplodedTaggedInPostsToQueryArray . "))
                            AND posteeid != '-1'
                            ORDER BY timeposted DESC
                            LIMIT 3
                    ";
       
                    if (!$result3 = mysqli_query($con,$sql)){
                        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                        
                    }
                    
                        while ($row = mysqli_fetch_array($result3)) {
                                      
                            $postid = $row['id'];
                            $posterid = $row['posterid'];
                            $posteeid = $row['posteeid'];
                            $htmlcontents = $row['htmlcontents'];
                            $taggedpeople = $row['taggedpeople'];
                            $wallid = $row['wallid'];
                            $postlikes = $row['postlikes'];

                            $taggedpeoplearray = explode(",",$taggedpeople);
                            
                            $timeposted = $row['timeposted'];

                            $_SESSION["oldestPostTimestampSoFar"] = $timeposted;
                            
                            $htmlcontents = stripslashes($htmlcontents);

                            $posterprofilesrc = $row['posterprofilesrc'];

                            $posterfirstname = $row['posterfirstname'];

                            $posterlastname = $row['posterlastname'];
                            
                                echo '
                                    <div class = "NewsfeedOnePost" id = "NewsfeedOnePost'.$postid.'">
                                        <div class = "NewsfeedOnePostInner">
                                            <div class = "NewsfeedOnePostTop">
                                                <img class = "NewsfeedOnePostProfileSrc" src = "'.$posterprofilesrc.'" />
                                                <div class = "NewsfeedOnePostNameDiv"><a class = "NameLink" href = "friendprofilefull.php?id='.$posterid.'">'.$posterfirstname.'&nbsp;'.$posterlastname.'</a>
                                                ';
                                                if (count($taggedpeoplearray) > 2){
                
                                                    $counter = 0;
                
                                                    while(count($taggedpeoplearray) > 0){
                                                        if ($counter == 0){
                                                            echo 'is with ';
                                                        }
                                                        else if (count($taggedpeoplearray) == 3){
                                                            echo ', and ';
                                                        }
                                                        else {
                                                            echo ', ';
                                                        }
                
                                                        echo '<a href = "http://www.socialnetwork.a2hosted.com/friendprofilefull.php?id='.$taggedpeoplearray[0].'">'
                                                        .$taggedpeoplearray[1].'&nbsp;'.$taggedpeoplearray[2].'</a>';
                
                
                                                        $taggedpeoplearray = array_slice($taggedpeoplearray,3);
                
                                                        $counter++;
                                                    }
                                                }
                                                
                                                echo '</div>
                                                <div class = "NewsfeedOnePostTimeDiv">'.date("F jS, Y", strtotime($timeposted)).'</div>
                                            </div>
                                            <div id = "NewsfeedPostHTMLContents'.$postid.'"> <script>document.write(decodeURI("'.$htmlcontents.'"));</script></div>
                                            
                                            <div class = "NewsfeedPostLikes" id = "NewsfeedPostLikes'.$postid.'">
                                                <img src = "images/circlelike.png" />
                                            </div>
                                        
                                            <div class = "LikeCommentShare" id = "LikeCommentShare'.$postid.'">
                                                <div class = "LikeDiv ItemButton" data-wall_id = "'.$posteeid.'" data-post_author_id="'.$posterid.'" id = "LikeDiv'.$postid.'" fullname = "'.$_SESSION['firstname'].' '.$_SESSION['lastname'].'">
                                                    <div class = "LikeInnerDiv">
                                                            <img src = "images/like.png" id = "LikeImage'.$postid.'" onclick = "toggleLike('.$postid.')"/>
                                                            Like
                                                    </div>
                                                </div>
                                                <div class = "CommentDiv ItemButton" id = "CommentDiv'.$postid.'">
                                                    <div class = "CommentInnerDiv">
                                                            Comment
                                                            <img src = "images/comment3.png" />
                                                    </div>
                                                </div>
                                                <div class = "ShareDiv ItemButton" id = "ShareDiv'.$postid.'">
                                                    <div class = "ShareInnerDiv">
                                                            Share
                                                            <img src = "images/share.png" />
                                                    </div>
                                                    <div class = "ShareDivHidden" id = "ShareDivHidden'.$postid.'">
                                                        <div class = "ShareDivHiddenSelf" data-id = "'.$postid.'">
                                                            Share now (Friends)
                                                        </div>

                                                        <div class = "ShareDivHiddenFriend" data-id = "'.$postid.'">
                                                            Share on a friend\'s profile
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class = "WriteCommentDiv" id = "WriteCommentDiv'.$postid.'">
                                                <img src = "'.$_SESSION["profile_src"].'" />
                                                <div id = "WriteComment'.$postid.'" contentEditable = "true" class = "NewsfeedMakeComment" data-CommentToMakeStatus = "comment" data-CurrentCommentStatus = "comment" data-wall_id = "'.$posteeid.'"data-PostAuthorID = "'.$posterid.'" name = "'.$posterid.'">
                                                    Write a comment...
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                
                                ';
                            
                                

                                if (strlen($postlikes) > 0){

                                    echo '
                                            <script>
                                                    
                                                    $("#NewsfeedPostLikes" + '.$postid.').css({"display": "block"});
                                            </script>
                                            ';


                                    $counter = 0;
                                    while (strlen($postlikes) > 0){   
                                        
                                            $oneID = substr($postlikes, 0, strpos($postlikes,","));    

                                            $oneName = substr($postlikes, strpos($postlikes,",") + 1, strpos($postlikes,";") - 2);
                                            
                                            
                                            if ($oneID == $myID){
                                                echo '<script>
                                                            $("#LikeImage" + '.$postid.').attr("src", "images/bluelike.png");
                                                            $("#LikeDiv" + '.$postid.').css("color", "#2078F5"); 
                                                    </script>';
                                            }



                                            if ($counter > 0){
                                                echo '
                                                <script>
                                                    $("#NewsfeedPostLikes" + '.$postid.').append(", ");
                                                </script>';
                                            }


                                            echo'<script>
                                                            $("#NewsfeedPostLikes" + '.$postid.').append("<a href = \'friendprofilefull.php?id='.$oneID.'\'>" + "'.$oneName.'" + "</a>");
                                                </script>';


                                            $postlikes = substr($postlikes, strpos($postlikes,";") + 1);    
                                            $counter++;
                                    }
                                }

                               
                            
                                $sql = "SELECT * FROM posts WHERE ultimateparentid = '$postid' ORDER BY timeposted ASC";
                                
                    
                                if (!$result2 = mysqli_query($con,$sql)){
                                    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                                                
                                }


                               
                               if (mysqli_num_rows($result2) > 0) {

                                            
                                            $parentID = $postid;

                                            
                                             generateComments($result2, $parentID);   
                                }
                    }            
        }

    

   
    
    
    function generateComments($result, $parentID) {
        
        while ($row = mysqli_fetch_array($result)){  //Write First Level of Comments

                    if ($row['parentid'] == $parentID){     
                            
                            echo '
                            <script>

                            var toInsert = "";

                            toInsert += "<br /><br />";
                            toInsert += "<div class = \'NewsfeedOneComment\' id = \'NewsfeedOneComment'.$row['id'].'\'>";
                            toInsert +=                "<img src = \''.$row['posterprofilesrc'].'\' />";
                            toInsert +=                "<div class = \'RightDiv\'>";
                                                                                        
                            toInsert +=                         "<div class = \'TextDiv\'>";
                            toInsert +=                                   "<div class = \'NameDiv\'>";
                            toInsert +=                                             "<a class = \'NameLink\' href = \'friendprofilefull.php?id='.$row['posterid'].'\'>'.$row['posterfirstname'].' '.$row['posterlastname'].'</a>";
                            toInsert +=                                   "</div>" + decodeURI("'.stripslashes($row['htmlcontents']).'");

                            toInsert +=                                   "<div class = \'CommentLikeDisplay\' id = \'CommentLikeDisplay'.$row['id'].'\'>";
                            toInsert +=                                            "<img src = \'images/bluelike.png\' />"
                            toInsert +=                                            "<div class = \'CommentLikeDisplayNumber\' id = \'CommentLikeDisplayNumber'.$row['id'].'\'>0</div>";      
                            toInsert +=                                   "</div>";
                                                               
                            toInsert +=                                   "<div class = \'CommentLikeDisplayPeople\' id = \'CommentLikeDisplayPeople'.$row['id'].'\'>";
                            toInsert +=                                             "<div class = \'ImageContainer\'><img src = \'images/bluelike.png\' /></div>";
                            ';

                            $postlikes = $row['postlikes'];

                            $postlikesarray = explode(";",$postlikes);

                            $postlikesnum = count($postlikesarray) - 1;

                            if ($postlikesnum == -1){
                                $postlikesnum = 0;
                            }
                                
                                echo 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleNum\' id = \'CommentLikeDisplayPeopleNum'.$row['id'].'\'>'.$postlikesnum.'</div><br /><br />";';
                                echo 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleList\' id = \'CommentLikeDisplayPeopleList'.$row['id'].'\'>";';
                            
                            if ($postlikesnum >= 1){
                                for ($i = 0; $i < $postlikesnum; $i++){
                                        $myValue = $postlikesarray[$i];
                                        $myMiniArray = explode(",",$myValue);
                                                echo '
                                                    toInsert +=                     "<span class = \"AddedName\">'.$myMiniArray[1].'<br /></span>"; //insert name to list
                                                
                                                ';
                                        
                                }
                            }
                            
                            echo 'toInsert +=                                       "</div>";'; //end CommentLikeDisplayPeopleList
                            
                            
                            
                            

                            echo '
                            
                            toInsert +=                                     "</div>"; //End CommentLikeDisplayPeople
                            toInsert +=                         "</div>"; //End TextDiv
                                                                                    
                            toInsert +=                         "<div class = \'OptionsDiv\'>"; 
                            toInsert +=                                   "<span class = \'LikeCommentLink\'  data-wall_id = \''.$row['wallid'].'\' data-post_author_id=\'" + '.$row['posterid'].' + "\' id = \'LikeCommentLink'.$row['id'].'\'>Like</span>"; 
                            toInsert +=                                            "&nbsp;&#183;&nbsp;";
                            toInsert +=                                   "<span class = \'ReplyCommentLink\' id = \'ReplyCommentLink'.$row['id'].'\' data-commentstatus = \'firstlevelcomment\'>Reply</span>";
                                                                            

                            toInsert +=                          "</div>"; 
                            toInsert +=                 "</div>"; //End RightDiv
                                                
                                                    
                                                    
                            toInsert += "</div>"; //End NewsfeedOneComment


                            toInsert += "        <div class = \'WriteReplyDiv\' id = \'WriteReplyDiv'.$row['id'].'\'>";
                            toInsert += "            <img src = \''.$_SESSION["profile_src"].'\' />";
                            toInsert += "            <div id = \'WriteReply'.$row['id'].'\' contentEditable = \'true\' class = \'NewsfeedMakeReply\' data-wall_id = \''.$row['wallid'].'\' data-CurrentCommentStatus = \'firstsubcomment\' data-CommentToMakeStatus = \'subcomment\'  data-parentid = \''.$row['id'].'\' data-ultimateparentid = \''.$row['ultimateparentid'].'\' data-PostAuthorID = \''.$row['posterid'].'\' name = \''.$row['posterid'].'\'>";
                            toInsert += "                Write a reply...";
                            toInsert += "            </div>";
                            toInsert += "        </div>";


                            toInsert += "<br /> <br /> "; 

							
                            $("#WriteCommentDiv'.$parentID.'").before(toInsert);
                            ';
                            if (strlen($postlikes) > 0){

                                        echo '
                                        
                                                $("#CommentLikeDisplay'.$row['id'].'").css("display", "block");

                                                $("#CommentLikeDisplayNumber'.$row['id'].'").text("'.$postlikesnum.'");


                                        ';
                                        
                                        for ($i = 0; $i < $postlikesnum; $i++){
                                            $myValue = $postlikesarray[$i];
                                            $myMiniArray = explode(",",$myValue);

                                            if ($myMiniArray[0] == $_SESSION["userid"]){
                                                    echo '

                                                                $("#LikeCommentLink" + '.$row['id'].').css("color", "#2078F4");
                                                            
                                                    ';
                                            }
                            }

                            }
                            
                            echo '
                            </script>
                            
                            ';

                        

                    }

        }

        mysqli_data_seek($result, 0);

        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        
        $result = array_reverse($data,true);
        
        foreach ($result as $row) {   //Write Second Level of Comments
         
        

        

                    if ($row['ultimateparentid'] == $parentID && $row['parentid'] != $parentID){ 
                        
                        echo '
                        <script>

                        var toInsert = "";

                        toInsert += "<br /><br />";
                        toInsert += "<div class = \'NewsfeedOneComment\' id = \'NewsfeedOneComment'.$row['id'].'\'>";
                        toInsert +=                "<img src = \''.$row['posterprofilesrc'].'\' />";
                        toInsert +=                "<div class = \'RightDiv\'>";
                                                                                    
                        toInsert +=                         "<div class = \'TextDiv\'>";
                        toInsert +=                                   "<div class = \'NameDiv\'>";
                        toInsert +=                                             "<a class = \'NameLink\' href = \'friendprofilefull.php?id='.$row['posterid'].'\'>'.$row['posterfirstname'].' '.$row['posterlastname'].'</a>";
                        toInsert +=                                   "</div>" + decodeURI("'.stripslashes($row['htmlcontents']).'");

                        toInsert +=                                   "<div class = \'CommentLikeDisplay\' id = \'CommentLikeDisplay'.$row['id'].'\'>";
                        toInsert +=                                            "<img src = \'images/bluelike.png\' />"
                        toInsert +=                                            "<div class = \'CommentLikeDisplayNumber\' id = \'CommentLikeDisplayNumber'.$row['id'].'\'>0</div>";      
                        toInsert +=                                   "</div>";
                                                           
                        toInsert +=                                   "<div class = \'CommentLikeDisplayPeople\' id = \'CommentLikeDisplayPeople'.$row['id'].'\'>";
                        toInsert +=                                             "<div class = \'ImageContainer\'><img src = \'images/bluelike.png\' /></div>";
                        ';

                        $postlikes = $row['postlikes'];

                        $postlikesarray = explode(";",$postlikes);

                        $postlikesnum = count($postlikesarray) - 1;

                        if ($postlikesnum == -1){
                            $postlikesnum = 0;
                        }
                            
                            echo 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleNum\' id = \'CommentLikeDisplayPeopleNum'.$row['id'].'\'>'.$postlikesnum.'</div><br /><br />";';
                            echo 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleList\' id = \'CommentLikeDisplayPeopleList'.$row['id'].'\'>";';
                        
                        if ($postlikesnum >= 1){
                            for ($i = 0; $i < $postlikesnum; $i++){
                                    $myValue = $postlikesarray[$i];
                                    $myMiniArray = explode(",",$myValue);
                                            echo '
                                                toInsert +=                     "<span class = \"AddedName\">'.$myMiniArray[1].'<br /></span>"; //insert name to list
                                            
                                            ';
                                    
                            }
                        }
                        
                        echo 'toInsert +=                                       "</div>";'; //end CommentLikeDisplayPeopleList
                        
                        
                        
                        

                        echo '
                        
                        toInsert +=                                     "</div>"; //End CommentLikeDisplayPeople
                        toInsert +=                         "</div>"; //End TextDiv
                                                                                
                        toInsert +=                         "<div class = \'OptionsDiv\'>"; 
                        toInsert +=                                   "<span class = \'LikeCommentLink\'  data-wall_id = \''.$row['wallid'].'\' data-post_author_id=\'" + '.$row['posterid'].' + "\' id = \'LikeCommentLink'.$row['id'].'\'>Like</span>"; 
                        toInsert +=                                            "&nbsp;&#183;&nbsp;";
                        toInsert +=                                   "<span class = \'ReplyCommentLink\' id = \'ReplyCommentLink'.$row['id'].'\' data-commentstatus = \'subcomment\' data-parentid = \''.$row['parentid'].'\'>Reply</span>";
                                                                        

                        toInsert +=                          "</div>"; 
                        toInsert +=                 "</div>"; //End RightDiv
                                            
                                                
                                                
                        toInsert += "</div>"; //End NewsfeedOneComment

                        


                        
                        $("#NewsfeedOneComment'.$row['parentid'].'").after("<br /><br />" + toInsert);



                        $("#NewsfeedOneComment'.$row['id'].'").css({"margin":"10px 0px 0px 45px","width":"497px"});
                        ';
                        if (strlen($postlikes) > 0){

                                    echo '
                                    
                                            $("#CommentLikeDisplay'.$row['id'].'").css("display", "block");

                                            $("#CommentLikeDisplayNumber'.$row['id'].'").text("'.$postlikesnum.'");


                                    ';
                                    
                                    for ($i = 0; $i < $postlikesnum; $i++){
                                        $myValue = $postlikesarray[$i];
                                        $myMiniArray = explode(",",$myValue);

                                        if ($myMiniArray[0] == $_SESSION["userid"]){
                                                echo '

                                                            $("#LikeCommentLink" + '.$row['id'].').css("color", "#2078F4");
                                                        
                                                ';
                                        }
                        }

                        }
                        
                        echo '
                        </script>
                        
                        ';

                    }



        }
		
		echo '<script>$("#WriteCommentDiv'.$parentID.'").before("<br /><br />");</script>';




    }
?>



</div>


<script>

$(".CommentDiv").on('click', function(event){
    let myID = $( this ).attr("id");

    let myNumber = myID.substring(10);



    $("#WriteComment" + myNumber).focus();


});

$("#ShareToFriendsWallCancelButton").on('click', function(event){
    
    $("#ShareToFriendsWall").hide();
    $("#OpaqueDiv").hide();

    
    
});

var currentHTMLToBeShared = "";

$(document).ready(function(){

$("#NewsfeedContainer").on('click', '.ShareDivHiddenSelf', function(event){ //
           
            $("#OpaqueDiv").show();
            $("#ShareToFriendsWall").show();
            $("#ShareToFriendsWallContentDiv").load("sharetofriendsprofile.php?share=self");
            var myPostID = $(this).data("id");
            currentHTMLToBeShared = $("#NewsfeedPostHTMLContents" + myPostID).html();
            var myIndex = currentHTMLToBeShared.indexOf("\<\/script\>");
            currentHTMLToBeShared = currentHTMLToBeShared.substring(myIndex + 9);
            
            showCreatePost(<?php echo $myID ?>, "<?php echo $_SESSION['firstname']; ?>");
            $("#CreateProfileInput").append("<br />" + currentHTMLToBeShared);
            
});

$("#NewsfeedContainer").on('click', '.ShareDivHiddenFriend', function(event){ //
            $("#OpaqueDiv").show();
            $("#ShareToFriendsWall").show();
            $("#ShareToFriendsWallContentDiv").load("sharetofriendsprofile.php?share=friend");
            var myPostID = $(this).data("id");
            currentHTMLToBeShared = $("#NewsfeedPostHTMLContents" + myPostID).html();
            var myIndex = currentHTMLToBeShared.indexOf("\<\/script\>");
            currentHTMLToBeShared = currentHTMLToBeShared.substring(myIndex + 9);
           
});

});




$("#NewsfeedContainer").on('click', '.ReplyCommentLink', function(event){ //top level posts LIKE button clicked
            let myID = $( this ).attr("id");
            let myNumber = myID.substring(16);
            
            if ($( this ).data("commentstatus") == "firstlevelcomment") {
                    if ($("#WriteReplyDiv" + myNumber + ":visible").length == 0){
                        
                        $("#WriteReplyDiv" + myNumber).css("display", "block");
                        $("#WriteReplyDiv" + myNumber).after("<br /><br />");
                    }
            }
            else {
                let myParentID = $( this ).data("parentid");
                if ($("#WriteReplyDiv" + myParentID + ":visible").length == 0){
                        
                        $("#WriteReplyDiv" + myParentID).css("display", "block");
                        $("#WriteReplyDiv" + myParentID).after("<br /><br />");
                    }
            }

});



$("#NewsfeedContainer").on('mouseenter', '.ShareDiv', function() { //display list of people who liked a comment or subcomment
    
    //stuff to do on mouse enter

    let myID = $( this ).attr("id");

    let myNumber = myID.substring(8);

    $("#ShareDivHidden" + myNumber).css("display", "block");  

});

$("#NewsfeedContainer").on('mouseleave', '.ShareDiv', function() { //display list of people who liked a comment or subcomment
    
    //stuff to do on mouse enter


    let myID = $( this ).attr("id");

    let myNumber = myID.substring(8);


    $("#ShareDivHidden" + myNumber).css("display", "none");

});

$("#NewsfeedContainer").on('click', '.LikeDiv', function(event){ //top level posts LIKE button clicked
           

            let myID = $( this ).attr("id");

            
            let myFullName = $( this ).attr("fullname");
            

            let myNumber = myID.substring(7);

            

            var mySrc = document.getElementById("LikeImage" + myNumber).src;

            if (mySrc == "https://www.socialnetwork.a2hosted.com/images/like.png"
                    || mySrc == "https://socialnetwork.a2hosted.com/images/like.png"){
               
                $("#LikeImage" + myNumber).attr("src", "images/bluelike.png");
                $("#LikeDiv" + myNumber).css("color", "#2078F5"); 

                if ($("#NewsfeedPostLikes" + myNumber).is(":visible")){
                    $("#NewsfeedPostLikes" + myNumber).append(", ");
                }
                else {
                   
                    $("#NewsfeedPostLikes" + myNumber).css({"display": "block"});
                }

                
                $("#NewsfeedPostLikes" + myNumber).append("<a href = \"friendprofilefull.php?id=" + + <?php echo $myID; ?> + "\">" + myFullName + "</a>");

                let post_author_id = $( this ).data('post_author_id');

              

                let wall_id = $( this ).data('wall_id');

                

                $.ajax({
                        url: 'addliketopost.php',
                        type: 'post',
                        data: {id:myNumber, post_author_id:post_author_id, wall_id:wall_id,type:"post"},

                        success: function(response){
                            if (response != ""){
                                alert(response);
                            }
                        },
				});

            }
            else {
             
                $("#LikeImage" + myNumber).attr("src", "https://socialnetwork.a2hosted.com/images/like.png");
                $("#LikeDiv" + myNumber).css("color", "black"); 
                var myString = $("#NewsfeedPostLikes" + myNumber).html();
                myString = myString.trim();
                
                myString = myString.substring(33,myString.length);
                myString = myString.trim();
               
                
                const myArr = myString.split(",");
              

                var index = myArr.indexOf("<a href=\"friendprofilefull.php?id=" + <?php echo $myID; ?> + "\">" + myFullName + "</a>");
                if (index == -1){
                    index = myArr.indexOf(" <a href=\"friendprofilefull.php?id=" + <?php echo $myID; ?> + "\">" + myFullName + "</a>");
                }
                
                if (index > -1) {
                    myArr.splice(index, 1);
                }

               
                
                var outputString = "";
                for (var i = 0; i < myArr.length; i++) {
                    if (i == 0 && myArr.length == 1){
                        outputString += myArr[i];
                    }
                    else if (i == myArr.length - 1) {
                        outputString += myArr[i];
                    }
                    else {
                        outputString += myArr[i] + ",";
                    }
                }
                if (myArr.length > 0){
                    outputString = "<img src = \"images/circlelike.png\" />" + outputString;
                }
                else {
                    outputString = "<img src = \"images/circlelike.png\" />";
                }
                

                $("#NewsfeedPostLikes" + myNumber).html(outputString);
               
                if (!outputString.includes("a href")){

                    $("#NewsfeedPostLikes" + myNumber).css({"display": "none"});

                }
                
                $.ajax({
                        url: 'deletelikefrompost.php',
                        type: 'post',
                        data: {id:myNumber},

                        success: function(response){
                            if (response != ""){
                                
                            }
                        },
				});
            }

  });

  $("#NewsfeedContainer").on('click', '.LikeCommentLink', function(event){ //comment or subcomment LIKE button clicked
            

            let myID = $( this ).attr("id");

            

            

            let myNumber = myID.substring(15);

           
            
            
            if ($("#LikeCommentLink" + myNumber).css("color") == "rgb(101, 103, 107)"){

                
                $('#CommentLikeDisplay' + myNumber).addClass('CommentLikeDisplay_is_shown');

                $("#CommentLikeDisplay" + myNumber).css("display", "block");
                
                var myLikesNum = parseInt($("#CommentLikeDisplayNumber" + myNumber).text());

                myLikesNum++;

                

                $("#CommentLikeDisplayNumber" + myNumber).text(myLikesNum.toString());

                $("#LikeCommentLink" + myNumber).css("color", "#2078F4");

                $("#CommentLikeDisplayPeople" + myNumber).css("display", "block");

                $("#CommentLikeDisplayPeopleNum" + myNumber).text(myLikesNum.toString());

                $("#CommentLikeDisplayPeopleList" + myNumber).append("<span class = \"AddedName\"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?><br /></span>");

                $("#CommentLikeDisplayPeople" + myNumber).css("display", "none");

                
                $('#CommentLikeDisplay' + myNumber).addClass('CommentLikeDisplay_is_shown');


                let post_author_id = $( this ).data('post_author_id');

                let wall_id = $( this ).data("wall_id");

               

                let myString = "comment";
                
                $.ajax({
                        url: 'addliketopost.php',
                        type: 'post',
                        data: {id:myNumber,post_author_id:post_author_id,myType:myString,wall_id:wall_id},

                        success: function(response){
                            if (response != ""){
                                alert(response);
                            }
                            
                        },
				});

            }
            else {

                
                var myLikesNum = parseInt($("#CommentLikeDisplayNumber" + myNumber).text());

                myLikesNum--;

                

                $("#CommentLikeDisplayNumber" + myNumber).text(myLikesNum.toString());

                $("#LikeCommentLink" + myNumber).css("color", "#65676B");

                if (myLikesNum == 0){
                    $("#CommentLikeDisplay" + myNumber).css("display", "none");
                }


                $("#CommentLikeDisplayPeople" + myNumber).css("display", "block");

                $("#CommentLikeDisplayPeopleNum" + myNumber).text(myLikesNum.toString());
 
                var myHTML = $("#CommentLikeDisplayPeopleList" + myNumber).html();

                var myString = myHTML.toString();

                

                let stringToDelete = "<span class=\"AddedName\"><?php echo $_SESSION['firstname'] .' '. $_SESSION['lastname'] ?><br></span>";

              

                myString = myHTML.replace(stringToDelete,'');

              
                

                $("#CommentLikeDisplayPeopleList" + myNumber).html(myString);

                $("#CommentLikeDisplayPeople" + myNumber).css("display", "none");
                
                $("#CommentLikeDisplay" + myNumber).removeClass("CommentLikeDisplay_is_shown");
                
              

                $.ajax({
                        url: 'deletelikefrompost.php',
                        type: 'post',
                        data: {id:myNumber},

                        success: function(response){
                            if (response != ""){
                                alert(response);
                            }
                        },
				});
            }
            

  });

  $("#NewsfeedContainer").on('mouseenter', '.CommentLikeDisplay', function() { //display list of people who liked a comment or subcomment
    
        //stuff to do on mouse enter

        

        let myID = $( this ).attr("id");

        let myNumber = myID.substring(18);

        

        $("#CommentLikeDisplayPeople" + myNumber).css("display", "block");

           

        
    });

    $("#NewsfeedContainer").on('mouseleave', '.CommentLikeDisplay', function() { //display list of people who liked a comment or subcomment
    
    //stuff to do on mouse enter

    

    let myID = $( this ).attr("id");

    let myNumber = myID.substring(18);

    

    $("#CommentLikeDisplayPeople" + myNumber).css("display", "none");

       

    
});
   

  





$("#NewsfeedContainer").on('keydown', ".NewsfeedMakeComment", function(e) {  
   
    if(e.keyCode == 13 && !$(this).html().includes("Write a comment...") && !$(this).html().includes("Write a reply..."))
    {
        
        event.preventDefault();
        let myID = $( this ).attr("id");
        let myNumber = myID.substring(12);

        let myContent = encodeURI($( this ).html());


        
        
        let UltimateParentID = myID.substring(12);

  
        
        var thisParentDiv = $( this ).parent();
        
        
        var PostAuthorID = $( this ).data("postauthorid");

        
        var wall_id = $( this ).data("wall_id");
       

        $(this).html("Write a comment...");

        

        $.ajax({
                        url: 'addCommentToPost.php',
                        type: 'post',
                        data: {PostCommentedOnID:myNumber, PostContent:myContent, UltimateParentID:UltimateParentID, PostAuthorID:PostAuthorID, wall_id:wall_id},
                        dataType: 'JSON',
                        success: function(response){
                            
                            if (response[0].info_was_inserted == "true"){
                          

                               

                                var toInsert = "";

                              
                                toInsert += "<div class = 'NewsfeedOneComment' id = 'NewsfeedOneComment'" + response[0].new_post_id + "'>";
                                toInsert +=                "<img src = '<?php echo $_SESSION['profile_src']; ?>' />";
                                toInsert +=                "<div class = 'RightDiv'>";
                                                                                            
                                toInsert +=                         "<div class = 'TextDiv'>";
                                toInsert +=                                   "<div class = 'NameDiv'>";
                                toInsert +=                                             "<a class = 'NameLink' href = 'myprofile.php'><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></a>";
                                toInsert +=                                   "</div> " + decodeURI(myContent);
                                
                                toInsert +=                                   "<div class = 'CommentLikeDisplay' id = 'CommentLikeDisplay" + response[0].new_post_id + "'>";
                                toInsert +=                                            "<img src = 'images/bluelike.png' />"
                                toInsert +=                                            "<div class = 'CommentLikeDisplayNumber' id = 'CommentLikeDisplayNumber" + response[0].new_post_id + "'>0</div>";      
                                toInsert +=                                   "</div>";
                                
                                toInsert +=                                   "<div class = 'CommentLikeDisplayPeople' id = 'CommentLikeDisplayPeople" + response[0].new_post_id + "'>";
                                toInsert +=                                            "<div class = 'ImageContainer'><img src = 'images/bluelike.png' /></div>";
       

                                toInsert +=                                             "<div class = 'CommentLikeDisplayPeopleNum' id = 'CommentLikeDisplayPeopleNum" + response[0].new_post_id + "'>0</div><br /><br />";
                                toInsert +=                                             "<div class = 'CommentLikeDisplayPeopleList' id = 'CommentLikeDisplayPeopleList" + response[0].new_post_id + "'>";
     
        
                               
                                toInsert +=                                             "</div>"; //End CommentLikeDisplayPeopleList

                                toInsert +=                                   "</div>"; //End CommentLikeDisplayPeople    

                                toInsert +=                         "</div>";
                                                                                          
                                toInsert +=                         "<div class = 'OptionsDiv'>"; 
                                toInsert +=                                   "<span class = 'LikeCommentLink'  data-wall_id = '" + wall_id + "' id = 'LikeCommentLink" + response[0].new_post_id + "' data-post_author_id = '" + PostAuthorID + "'>Like</span>"; 
                                toInsert +=                                            "&nbsp;&#183;&nbsp;";
                                toInsert +=                                   "<span class = 'ReplyCommentLink' id = 'ReplyCommentLink" + response[0].new_post_id + "' data-commentstatus = 'firstlevelcomment' >Reply</span>";
                                                                                
                                
                                toInsert +=                          "</div>"; 
                                toInsert +=                 "</div>"; 
                                                    
                                                        

                                toInsert += "</div>"; 
                                toInsert += "        <div class = 'WriteReplyDiv' id = 'WriteReplyDiv" + response[0].new_post_id + "'>";
                                toInsert += "            <img src = '<?php echo $_SESSION['profile_src']; ?>' />";
                                toInsert += "            <div id = 'WriteReply" + response[0].new_post_id + "' contentEditable = 'true' class = 'NewsfeedMakeReply' data-wall_id = '" + wall_id + "' data-CurrentCommentStatus = \'firstsubcomment\' data-CommentToMakeStatus = 'subcomment' data-parentid = '" + response[0].new_post_id + "' data-ultimateparentid = '" + UltimateParentID + "' data-PostAuthorID = '<?php echo $myID; ?>' name = '<?php echo $myID; ?>'>";
                                toInsert += "                Write a reply...";
                                toInsert += "            </div>";
                                toInsert += "        </div>";
                                toInsert += "<br /> <br /> <br /> <br />"; 

                                     thisParentDiv.before(toInsert);
                                     $(this).html("Write a comment...");

                                     
                            }

                            else {
                                
                                alert(response[0].message);
                                
                            }

                            
                        }, 
                        
		});

        

        
        //post comment
    }
    else 
    {
        if ($(this).html().includes("Write a comment...")){
            if (e.keyCode == 13){
               
                event.preventDefault();
            }
            else {
                $(this).html("");
            }
        }
    }
    
});

$("#NewsfeedContainer").on('keydown', ".NewsfeedMakeReply", function(e) {  
 
    if(e.keyCode == 13 && !$(this).html().includes("Write a comment...") && !$(this).html().includes("Write a reply..."))
    {
        
        event.preventDefault();
       
        let myParentSubcommentID = $( this ).data("parentid");

        let myContent = encodeURI($( this ).html());


    
        
        let UltimateParentID = $( this ).data("ultimateparentid");;

  
        
        var thisParentDiv = $( this ).parent();
        
       
        var PostAuthorID = $( this ).data("postauthorid");

      
        var wall_id = $( this ).data("wall_id");
       

        $(this).html("Write a reply...");

      
        $.ajax({
                        url: 'addCommentToPost.php',
                        type: 'post',
                        data: {PostCommentedOnID:myParentSubcommentID, PostContent:myContent, UltimateParentID:UltimateParentID, PostAuthorID:PostAuthorID, wall_id:wall_id},
                        dataType: 'JSON',
                        success: function(response){
                            
                            if (response[0].info_was_inserted == "true"){
                                

                                

                                var toInsert = "";
                                toInsert += "<br /><br />";
                            
                                if ($( "#WriteReply" + myParentSubcommentID ).data("currentcommentstatus") == "subsequentsubcomment"){
                                    toInsert += "<br />";
                                    
                                }
                                

                                

                                toInsert += "<div class = 'NewsfeedOneComment' id = 'NewsfeedOneComment" + response[0].new_post_id + "'>";
                                toInsert +=                "<img src = '<?php echo $_SESSION['profile_src']; ?>' />";
                                toInsert +=                "<div class = 'RightDiv'>";
                                                                                            
                                toInsert +=                         "<div class = 'TextDiv'>";
                                toInsert +=                                   "<div class = 'NameDiv'>";
                                toInsert +=                                             "<a class = 'NameLink' href = 'myprofile.php'><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></a>";
                                toInsert +=                                   "</div> " + decodeURI(myContent);
                                
                                toInsert +=                                   "<div class = 'CommentLikeDisplay' id = 'CommentLikeDisplay" + response[0].new_post_id + "'>";
                                toInsert +=                                            "<img src = 'images/bluelike.png' />"
                                toInsert +=                                            "<div class = 'CommentLikeDisplayNumber' id = 'CommentLikeDisplayNumber" + response[0].new_post_id + "'>0</div>";      
                                toInsert +=                                   "</div>";
                                
                                toInsert +=                                   "<div class = 'CommentLikeDisplayPeople' id = 'CommentLikeDisplayPeople" + response[0].new_post_id + "'>";
                                toInsert +=                                            "<div class = 'ImageContainer'><img src = 'images/bluelike.png' /></div>";
       

                                toInsert +=                                             "<div class = 'CommentLikeDisplayPeopleNum' id = 'CommentLikeDisplayPeopleNum" + response[0].new_post_id + "'>0</div><br /><br />";
                                toInsert +=                                             "<div class = 'CommentLikeDisplayPeopleList' id = 'CommentLikeDisplayPeopleList" + response[0].new_post_id + "'>";
     
        
                                //toInsert +=                                                     "<span class = 'AddedName'><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?><br /></span>";
                                toInsert +=                                             "</div>"; //End CommentLikeDisplayPeopleList

                                toInsert +=                                   "</div>"; //End CommentLikeDisplayPeople    

                                toInsert +=                         "</div>";
                                                                                          
                                toInsert +=                         "<div class = 'OptionsDiv'>"; 
                                toInsert +=                                   "<span class = 'LikeCommentLink'  data-wall_id = '" + wall_id + "' id = 'LikeCommentLink" + response[0].new_post_id + "' data-post_author_id = '" + PostAuthorID + "'>Like</span>"; 
                                toInsert +=                                            "&nbsp;&#183;&nbsp;";
                                toInsert +=                                   "<span class = 'ReplyCommentLink' id = 'ReplyCommentLink" + response[0].new_post_id + "' data-commentstatus = 'subcomment' data-parentid = '" + myParentSubcommentID + "'>Reply</span>";
                                                                                
                                
                                toInsert +=                          "</div>"; 
                                toInsert +=                 "</div>"; 
                                                    
                                                        
                                              
                                toInsert += "</div>"; 
                                

                                     thisParentDiv.before("<br /><br />");
                                     thisParentDiv.before(toInsert);
                                     $(this).html("Write a reply...");
                                     

                                     $('#NewsfeedOneComment' + response[0].new_post_id).css({"margin":"10px 0px -22px 45px","width":"497px"});
                                     
                                     
                                     $( "#WriteReplyDiv" + myParentSubcommentID ).after("<br /><br />");
                            
                                     $( "#WriteReply" + myParentSubcommentID ).data("currentcommentstatus", "subsequentsubcomment");
                            }

                            else {
                               
                                alert(response[0].message);
                                
                            }

                            
                        }, 
                        
		});

        

        
        //post comment
    }
    else 
    {
        if ($(this).html().includes("Write a reply...")){
            if (e.keyCode == 13){
                
                event.preventDefault();
            }
            else {
                $(this).html("");
            }
        }
    }
    
});


var endOfPostsReached = false;



var myDate = new Date();
var lastCalledAjaxTime = myDate.getTime();

window.onscroll = function() {
    
    if (!endOfPostsReached){

        if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight + 400) {
           
                var currentDate = new Date();

                if (!endOfPostsReached && (currentDate.getTime() > lastCalledAjaxTime + 1000)) {
                   
                    
                    lastCalledAjaxTime = currentDate.getTime();
                 
                    $.ajax({
                        url: 'newsfeedGetMorePosts.php?wallid=<?php echo $_SESSION['friendid']; ?>',
                        type: 'post',
                        data: {myFriendsIDs:myFriendsIDs,myFriendsTaggedPostIDs:myFriendsTaggedPostIDs,friendCount:friendCount,
                        myID:<?php echo $myID ?>,},
                        
                        success: function(response){
                            
                            if (!endOfPostsReached){
                                   
                                    if (response == "-1"){
                                        
                                        $("#NewsfeedContainer").append('<div id = "NoNewPosts">No Additional Posts</div>');
                                        endOfPostsReached = true;
                                    }
                                    else {
                                        
                                        $("#NewsfeedContainer").append(response);
                                        
                                        
                                    }
                            }
                        },
                    });
                }
        }


    }
   
}

</script>

</body>

</html>