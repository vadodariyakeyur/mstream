<?php

$songQuery = mysqli_query($con,"SELECT * FROM songs ORDER BY RAND() LIMIT 10");
$resultArray = array();

while($row = mysqli_fetch_array($songQuery)){
    array_push($resultArray,$row['id']);
}

$jsonArray = json_encode($resultArray); 
?>

<script>

$(document).ready(function(){
    var newPlaylist = <?php echo $jsonArray; ?>;
    audioElement = new Audio();
    setTrack(newPlaylist[0],newPlaylist,false);

    $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove",function(e){
        e.preventDefault();
    });

    $(".playbackBar .progressBar").mousedown(function(){
        mouseDown = true;
    });

    $(".playbackBar .progressBar").mousemove(function(e){
        if(mouseDown){
            timeFromOffset(e,this);
        }
    });

    $(".playbackBar .progressBar").mouseup(function(e){
        timeFromOffset(e,this);
    });

    $(".volumeBar .progressBar").mousedown(function(){
        mouseDown = true;
    });

    $(".volumeBar .progressBar").mousemove(function(e){
        if(mouseDown){
            var percentage = e.offsetX / $(this).width();
            if(percentage>=0 && percentage<=1){
                audioElement.audio.volume = percentage;
            }
        }
    });

    $(".volumeBar .progressBar").mouseup(function(e){
        if(mouseDown){
            var percentage = e.offsetX / $(this).width();
            if(percentage>=0 && percentage<=1){
                audioElement.audio.volume = percentage;
            }
        }
    });

    $(document).mouseup(function(){
        mouseDown=false;
    })
});

function timeFromOffset(mouse, progressBar){
    var percentage = (mouse.offsetX / $(progressBar).width())*100;
    var seconds = audioElement.audio.duration * percentage/100;
    audioElement.setTime(seconds);
}

function prevSong(){ 
    if(currentIndex==0){
        audioElement.setTime(0);
    }
    else{
        currentIndex--;
        setTrack(currentPlaylist[currentIndex],currentPlaylist,true);
    }
}

function nextSong(){

    if(repeat){
        audioElement.setTime(0);
        playSong();
        return
    }

    if(currentIndex == currentPlaylist.length - 1){
        currentIndex = 0;
    }
    else{
        currentIndex++;
    }

    var trackToPlay = shuffle ? shufflePlaylist[currentIndex] :currentPlaylist[currentIndex];
    setTrack(trackToPlay,currentPlaylist,true);
}

function setRepeat(){
    repeat = !repeat;
    var imgName = repeat?"repeat-active.png":"repeat.png";

    $(".controlButton.repeat img").attr("src","assets/images/icons/" + imgName);
}

function setMute(){
    audioElement.audio.muted = !audioElement.audio.muted;
    var imgName = audioElement.audio.muted?"volume-mute.png":"volume.png";

    $(".controlButton.volume img").attr("src","assets/images/icons/"+imgName);

}

function setShuffle(){
    shuffle = !shuffle;
    var imgName = shuffle?"shuffle-active.png":"shuffle.png";
    $(".controlButton.shuffle img").attr("src","assets/images/icons/"+imgName);

    if(shuffle){
        shuffleArray(shufflePlaylist);
        currentIndex=shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
    else{
        currentIndex=currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
}

function shuffleArray(a){
    var i,j;
    for(i=a.length;i;i--){
        j=Math.floor(Math.random()*i);
        [a[i-1],a[j]]=[a[j],a[i-1]]
    }
}

function setTrack(trackId,newPlaylist,play){

    if(newPlaylist!=currentPlaylist){
        currentPlaylist = newPlaylist;
        shufflePlaylist = currentPlaylist.slice();
        shuffleArray(shufflePlaylist);
    }

    if(shuffle){
        currentIndex = shufflePlaylist.indexOf(trackId);
    }
    else{
        currentIndex = currentPlaylist.indexOf(trackId);
    }
    pauseSong();

    $.post("includes/handlers/ajax/getSongJson.php",{songId: trackId}, function(data){

        data = JSON.parse(data);
        $(".trackName span").text(data.title);

        $.post("includes/handlers/ajax/getArtistJson.php",{artistId: data.artist}, function(data){
            var artist =JSON.parse(data);
            $(".artistName span").text(artist.name);
            $(".artistName span").attr("onclick","openPage('artist.php?id="+artist.id+"')");
        });
        
        $.post("includes/handlers/ajax/getAlbumJson.php",{albumId: data.album}, function(data){
            var album =JSON.parse(data);
            $(".albumLink img").attr("src",album.artworkPath);
            $(".albumLink img").attr("onclick","openPage('album.php?id="+album.id+"')");
            $(".trackName span").attr("onclick","openPage('album.php?id="+album.id+"')");
        });

        audioElement.setTrack(data);
        
        if(play==true){
            audioElement.play();
            $(".controlButton.play").hide();
            $(".controlButton.pause").show();
        }

    });

}

function playSong(){

    if(audioElement.audio.currentTime==0){
        $.post("includes/handlers/ajax/updatePlays.php",{songId: audioElement.currentlyPlaying.id});
    }

    $(".controlButton.play").hide();
    $(".controlButton.pause").show();
    audioElement.play();
}

function pauseSong(){
    $(".controlButton.pause").hide();
    $(".controlButton.play").show();
    audioElement.pause();
}

</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">

        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img class="albumArtwork" role="link" tabindex="0" src="" alt="Album Artwork">
                </span>
                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0">
                            <!-- Track Name Using setTrack function -->
                        </span>
                    </span>
                    <span class="artistName">
                        <span role="link" tabindex="0" >
                            <!-- Track Name Using setTrack function -->
                        </span>
                        </span>
                </div>
            </div>
        </div>

        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="Shuffle Songs" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>
                    <button class="controlButton previous" title="Previous Song" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>
                    <button class="controlButton play" title="Play Song" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>
                    <button class="controlButton pause" title="Pause Song" style="display: none;" onclick=pauseSong()>
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>
                    <button class="controlButton next" title="Next Song" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>
                    <button class="controlButton repeat" title="Repeat Song" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>
                </div>
                <div class="playbackBar">
                    <span class="progresstime current">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progresstime remaining">0.00</span>
                </div>
            </div>
        </div>

        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume" onclick="setMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume Button">
                </button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>