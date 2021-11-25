<?php session_start();
include("config.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = "";


    
$myID = $_GET["userid"];






$oldestPostTimestampSoFar = $_SESSION["oldestPostTimestampSoFar"];

$sql = "select * from posts where posteeid = '$myID' AND timeposted < '$oldestPostTimestampSoFar' order by timeposted desc limit 3";
       
if (!$result = mysqli_query($con,$sql)){
    $message ='<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
    $return_arr[] = array("query_successful" => "false", "query_message" => $message);
    // Encoding array in JSON format
    echo json_encode($return_arr);
    
}
if (mysqli_num_rows($result) == 0) {
    
    $return_arr[] = array("query_successful" => "true", "query_message" => "No More Posts");
    // Encoding array in JSON format
    echo json_encode($return_arr);
}
else {
    while ($row = mysqli_fetch_array($result)) {
                                      
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
            
                $response .= '
                    <div class = "PostsOnePost" id = "PostsOnePost'.$postid.'">
                        <div class = "PostsOnePostInner">
                            <div class = "PostsOnePostTop" id = "PostsOnePostTop'.$postid.'">
                                <img class = "PostsOnePostProfileSrc" src = "'.$_SESSION["profile_src"].'" />
                                <div class = "PostsOnePostNameDiv"><a class = "NameLink" href = "friendprofilefull.php?id='.$posterid.'">'.$_SESSION["firstname"].'&nbsp;'.$_SESSION["lastname"].'</a>
                                ';
                                if (count($taggedpeoplearray) > 2){

                                    $counter = 0;

                                    while(count($taggedpeoplearray) > 0){
                                        if ($counter == 0){
                                            $response .= 'is with ';
                                        }
                                        else if (count($taggedpeoplearray) == 3){
                                            $response .= ', and ';
                                        }
                                        else {
                                            $response .= ', ';
                                        }

                                        $response .= '<a href = "http://www.socialnetwork.a2hosted.com/friendprofilefull.php?id='.$taggedpeoplearray[0].'">'
                                        .$taggedpeoplearray[1].'&nbsp;'.$taggedpeoplearray[2].'</a>';


                                        $taggedpeoplearray = array_slice($taggedpeoplearray,3);

                                        $counter++;
                                    }
                                }
                                
                                $response .= '</div>
                                <div class = "PostsOnePostTimeDiv">'.date("F jS, Y", strtotime($timeposted)).'</div>
                            </div>
                            <div id = "PostsPagePostHTMLContents'.$postid.'"><script>$("#PostsOnePostTop'.$postid.'").after(decodeURI("'.$htmlcontents.'"));</script></div>
                        
                        
                            <div class = "PostsPagePostLikes" id = "PostsPagePostLikes'.$postid.'">
                                <img src = "images/circlelike.png" />
                            </div>
                        
                            <div class = "LikeCommentShare" id = "LikeCommentShare'.$postid.'">
                                <div class = "LikeDiv ItemButton" data-wall_id = "'.$posteeid.'" data-post_author_id="'.$posterid.'" id = "LikeDiv'.$postid.'" data-fullname = "'.$_SESSION['firstname'].' '.$_SESSION['lastname'].'">
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
                                <div id = "WriteComment'.$postid.'" contentEditable = "true" class = "PostsPageMakeComment" data-CommentToMakeStatus = "comment" data-CurrentCommentStatus = "comment" data-wall_id = "'.$posteeid.'"data-PostAuthorID = "'.$posterid.'" name = "'.$posterid.'">
                                    Write a comment...
                                </div>
                            </div>

                        
                        </div>
                    </div>
                
                ';
            
                if (strlen($postlikes) > 0){

                    $response .= '
                            <script>
                                    
                                    $("#PostsPagePostLikes" + '.$postid.').css({"display": "block"});
                            </script>
                            ';


                    $counter = 0;
                    while (strlen($postlikes) > 0){   
                        
                            $oneID = substr($postlikes, 0, strpos($postlikes,","));    

                            $oneName = substr($postlikes, strpos($postlikes,",") + 1, strpos($postlikes,";") - 2);
                            
                            
                            if ($oneID == $myID){
                                $response .= '<script>
                                            $("#LikeImage" + '.$postid.').attr("src", "images/bluelike.png");
                                            $("#LikeDiv" + '.$postid.').css("color", "#2078F5"); 
                                    </script>';
                            }



                            if ($counter > 0){
                                $response .= '
                                <script>
                                    $("#PostsPagePostLikes" + '.$postid.').append(", ");
                                </script>';
                            }


                            $response .= '<script>
                                            $("#PostsPagePostLikes" + '.$postid.').append("<a href = \'friendprofilefull.php?id='.$oneID.'\'>" + "'.$oneName.'" + "</a>");
                                </script>';


                            $postlikes = substr($postlikes, strpos($postlikes,";") + 1);    
                            $counter++;
                    }
                }

              
            
                $sql = "SELECT * FROM posts WHERE ultimateparentid = '$postid' ORDER BY timeposted ASC";
                
    
                if (!$result2 = mysqli_query($con,$sql)){
                    $message ='<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                    $return_arr[] = array("query_successful" => "false", "query_message" => $message);
                    // Encoding array in JSON format
                    echo json_encode($return_arr);
                                
                }


               
               if (mysqli_num_rows($result2) > 0) {

                            
                            $parentID = $postid;

                            
                             generateComments($result2, $parentID);   
                }
                        
            

    }//end while

$return_arr[] = array("query_successful" => "true", "query_message" => $response);
// Encoding array in JSON format
echo json_encode($return_arr);
        
}//end else
            

function generateComments($result3, $parentID) {
    global $response;

    while ($row = mysqli_fetch_array($result3)){  //Write First Level of Comments
                
                if ($row['parentid'] == $parentID){  
                    
                        
                        //$response .= '<script>alert("$parentID: '.$parentID.' and row[parentid] : '.$row['parentid'].'");</script>';
                        
                        $response .= '
                        <script>

                        var toInsert = "";

                        toInsert += "<br /><br />";
                        toInsert += "<div class = \'PostsPageOneComment\' id = \'PostsPageOneComment'.$row['id'].'\'>";
                        toInsert +=                "<img src = \''.$row['posterprofilesrc'].'\' />";
                        toInsert +=                "<div class = \'RightDiv\'>";
                                                                                    
                        toInsert +=                         "<div class = \'TextDiv\'>";
                        toInsert +=                                   "<div class = \'NameDiv\'>";
                        toInsert +=                                             "<a class = \'NameLink\' href = \'friendprofilefull.php?id='.$row['posterid'].'\'> '.$row['posterfirstname'].' '.$row['posterlastname'].'</a>";
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
                            
                            $response .= 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleNum\' id = \'CommentLikeDisplayPeopleNum'.$row['id'].'\'>'.$postlikesnum.'</div><br /><br />";';
                            $response .= 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleList\' id = \'CommentLikeDisplayPeopleList'.$row['id'].'\'>";';
                        
                        if ($postlikesnum >= 1){
                            for ($i = 0; $i < $postlikesnum; $i++){
                                    $myValue = $postlikesarray[$i];
                                    $myMiniArray = explode(",",$myValue);
                                            $response .= '
                                                toInsert +=                     "<span class = \"AddedName\">'.$myMiniArray[1].'<br /></span>"; //insert name to list
                                            
                                            ';
                                    
                            }
                        }
                        
                        $response .= 'toInsert +=                                       "</div>";'; //end CommentLikeDisplayPeopleList
                        
                        
                        
                        

                        $response .= '
                        
                        toInsert +=                                     "</div>"; //End CommentLikeDisplayPeople
                        toInsert +=                         "</div>"; //End TextDiv
                                                                                
                        toInsert +=                         "<div class = \'OptionsDiv\'>"; 
                        toInsert +=                                   "<span class = \'LikeCommentLink\'  data-wall_id = \''.$row['wallid'].'\' data-post_author_id=\'" + '.$row['posterid'].' + "\' id = \'LikeCommentLink'.$row['id'].'\'>Like</span>"; 
                        toInsert +=                                            "&nbsp;&#183;&nbsp;";
                        toInsert +=                                   "<span class = \'ReplyCommentLink\' id = \'ReplyCommentLink'.$row['id'].'\' data-commentstatus = \'firstlevelcomment\'>Reply</span>";
                                                                        

                        toInsert +=                          "</div>"; 
                        toInsert +=                 "</div>"; //End RightDiv
                                            
                                                
                                                
                        toInsert += "</div>"; //End PostsPageOneComment


                        toInsert += "        <div class = \'WriteReplyDiv\' id = \'WriteReplyDiv'.$row['id'].'\'>";
                        toInsert += "            <img src = \''.$_SESSION["profile_src"].'\' />";
                        toInsert += "            <div id = \'WriteReply'.$row['id'].'\' contentEditable = \'true\' class = \'PostsPageMakeReply\' data-wall_id = \''.$row['wallid'].'\' data-CurrentCommentStatus = \'firstsubcomment\' data-CommentToMakeStatus = \'subcomment\'  data-parentid = \''.$row['id'].'\' data-ultimateparentid = \''.$row['ultimateparentid'].'\' data-PostAuthorID = \''.$row['posterid'].'\' name = \''.$row['posterid'].'\'>";
                        toInsert += "                Write a reply...";
                        toInsert += "            </div>";
                        toInsert += "        </div>";


                        toInsert += "<br /> <br /> "; 

                        
                        $("#WriteCommentDiv'.$parentID.'").before(toInsert);
                        ';
                        if (strlen($postlikes) > 0){

                                    $response .= '
                                    
                                            $("#CommentLikeDisplay'.$row['id'].'").css("display", "block");

                                            $("#CommentLikeDisplayNumber'.$row['id'].'").text("'.$postlikesnum.'");


                                    ';
                                    
                                    for ($i = 0; $i < $postlikesnum; $i++){
                                        $myValue = $postlikesarray[$i];
                                        $myMiniArray = explode(",",$myValue);

                                        if ($myMiniArray[0] == $_SESSION["userid"]){
                                                $response .= '

                                                            $("#LikeCommentLink" + '.$row['id'].').css("color", "#2078F4");
                                                        
                                                ';
                                        }
                        }

                        }
                        
                        $response .= '
                        </script>
                        
                        ';

                    

                }

    }

    mysqli_data_seek($result3, 0);

    while($row = mysqli_fetch_assoc($result3)){
        $data[] = $row;
    }
    
    $result3 = array_reverse($data,true);
    
    foreach ($result3 as $row) {  //Write Second Level of Comments

 

                if ($row['ultimateparentid'] == $parentID && $row['parentid'] != $parentID){ 

                    $response .= '
                    <script>

                    var toInsert = "";

                    toInsert += "<br /><br />";
                    toInsert += "<div class = \'PostsPageOneComment\' id = \'PostsPageOneComment'.$row['id'].'\'>";
                    toInsert +=                "<img src = \''.$row['posterprofilesrc'].'\' />";
                    toInsert +=                "<div class = \'RightDiv\'>";
                                                                                
                    toInsert +=                         "<div class = \'TextDiv\'>";
                    toInsert +=                                   "<div class = \'NameDiv\'>";
                    toInsert +=                                             "<a href = \'friendprofilefull.php?id='.$row['posterid'].'\' class = \'NameLink\'>'.$row['posterfirstname'].' '.$row['posterlastname'].'</a>";
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
                        
                        $response .= 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleNum\' id = \'CommentLikeDisplayPeopleNum'.$row['id'].'\'>'.$postlikesnum.'</div><br /><br />";';
                        $response .= 'toInsert +=                                   "<div class = \'CommentLikeDisplayPeopleList\' id = \'CommentLikeDisplayPeopleList'.$row['id'].'\'>";';
                    
                    if ($postlikesnum >= 1){
                        for ($i = 0; $i < $postlikesnum; $i++){
                                $myValue = $postlikesarray[$i];
                                $myMiniArray = explode(",",$myValue);
                                        $response .= '
                                            toInsert +=                     "<span class = \"AddedName\">'.$myMiniArray[1].'<br /></span>"; //insert name to list
                                        
                                        ';
                                
                        }
                    }
                    
                    $response .= 'toInsert +=                                       "</div>";'; //end CommentLikeDisplayPeopleList
                    
                    
                    
                    

                    $response .= '
                    
                    toInsert +=                                     "</div>"; //End CommentLikeDisplayPeople
                    toInsert +=                         "</div>"; //End TextDiv
                                                                            
                    toInsert +=                         "<div class = \'OptionsDiv\'>"; 
                    toInsert +=                                   "<span class = \'LikeCommentLink\'  data-wall_id = \''.$row['wallid'].'\' data-post_author_id=\'" + '.$row['posterid'].' + "\' id = \'LikeCommentLink'.$row['id'].'\'>Like</span>"; 
                    toInsert +=                                            "&nbsp;&#183;&nbsp;";
                    toInsert +=                                   "<span class = \'ReplyCommentLink\' id = \'ReplyCommentLink'.$row['id'].'\' data-commentstatus = \'subcomment\' data-parentid = \''.$row['parentid'].'\'>Reply</span>";
                                                                    

                    toInsert +=                          "</div>"; 
                    toInsert +=                 "</div>"; //End RightDiv
                                        
                                            
                                            
                    toInsert += "</div>"; //End PostsPageOneComment

                    

                    //toInsert += "<br /> <br /> "; 

                    
                    $("#PostsPageOneComment'.$row['parentid'].'").after("<br /><br />" + toInsert);

                    $("#PostsPageOneComment'.$row['id'].'").css({"margin":"10px 0px 0px 45px","width":"497px"});
                    ';
                    if (strlen($postlikes) > 0){

                                $response .= '
                                
                                        $("#CommentLikeDisplay'.$row['id'].'").css("display", "block");

                                        $("#CommentLikeDisplayNumber'.$row['id'].'").text("'.$postlikesnum.'");


                                ';
                                
                                for ($i = 0; $i < $postlikesnum; $i++){
                                    $myValue = $postlikesarray[$i];
                                    $myMiniArray = explode(",",$myValue);

                                    if ($myMiniArray[0] == $_SESSION["userid"]){
                                            $response .= '

                                                        $("#LikeCommentLink" + '.$row['id'].').css("color", "#2078F4");
                                                    
                                            ';
                                    }
                    }

                    }
                    
                    $response .= '
                    </script>
                    
                    ';

                }



    }
    
    $response .= '<script>$("#WriteCommentDiv'.$parentID.'").before("<br /><br />");</script>';




}




function timeAgo($timestamp){
    $datetime1=new DateTime("now");
    $datetime2=date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
        $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

    }
    else if($diff->m > 0){
     $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
    }
    else if($diff->d > 0){
     $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
    }
    else if($diff->h > 0){
     $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
    }
    else if($diff->i > 0){
     $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
    }
    else if($diff->s > 0){
     $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
    }

$timemsg = $timemsg.' ago';
return $timemsg;
}


?>

