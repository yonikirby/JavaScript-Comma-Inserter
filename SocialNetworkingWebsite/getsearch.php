<?php

//https://makitweb.com/make-autocomplete-search-jquery-ajax/
//https://www.w3schools.com/howto/howto_js_autocomplete.asp

include("config.php");



// Search result

    $searchText = mysqli_real_escape_string($con,$_POST['search']);

	

    $sql = "SELECT id, firstname, lastname, profilephotopath FROM userinfo where firstname like '%".$searchText."%' order by firstname asc limit 5";

    //$result = mysqli_query($con,$sql);

	if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            //mysqli_close($con);
    }

    $search_arr = array();

    while($fetch = mysqli_fetch_assoc($result)){
        $id = $fetch['id'];
        $name = $fetch['firstname'] . " " . $fetch['lastname'];
        $profilephotopath = $fetch['profilephotopath'];
        $search_arr[] = array("id" => $id, "name" => $name, "profilephotopath" => $profilephotopath);
    }

    echo json_encode($search_arr);








?>
