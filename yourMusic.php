<?php
include("includes/includedFiles.php");
?>

<div class="playlistsContainer">
    <div class="gridViewContainer">
        <h2>PLAYLISTS</h2>

        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">New Playlist</button>
        </div>

        <div class="gridViewContainer">
            <h2>Albums</h2>
            <?php

                $username = $userLoggedIn->getUsername();

                $playlistQuery = mysqli_query($con,"SELECT * FROM playlists WHERE owner='$username'");

                if(mysqli_num_rows($playlistQuery) == 0){
                    echo "<span class='noResults'>You don't have any Playlist</span>";
                    exit();
                }

                while($row = mysqli_fetch_array($playlistQuery)){

                    echo "<div class='gridViewItem'>

                            <div class='gridViewInfo'>".$row['name']."</div>

                        </div>";
                }

            ?>

        </div>
    </div>
</div>