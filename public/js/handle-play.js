var storedMedia;
var playlists;
var player = document.getElementById("main");
let currentTimeout; // Variable to hold the current timeout
let mediaData = [];
let currentIndex = 0;
let currentPlaylistIndex = 0;
let currentPlaylistId;
let currentAllowAutoplay=false;
let currentScheduleTimeout;
// let bgPlaylistMusic = [];//uncomment for background music
const daysOfWeek = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
];
// let bg_music_audio = document.getElementById("bg_music"); //uncomment for background music

// Check if the play attempt was successful.
async function canAutoplayVideo(src) {
    const video = document.createElement("video");
    video.muted = true;
    video.src = src; // You need to provide a valid video source

    try {
        await video.play();
        return true;
    } catch (error) {
        return false;
    }
}

// check if allow play the playlist  dependent on it schedule

function isPlaylistScheduleAllowed(schedule) {
    const currentDate = new Date();

    // Get the current day of the week

    const currentDayOfWeek = daysOfWeek[currentDate.getDay()];

    // Check if the current day is in the allowed days of the week
    if (!schedule.days_of_week.includes(currentDayOfWeek)) {
        return false;
    }

    // Get the current date, time, and the schedule's start and end dates and times
    const currentDateString = currentDate.toISOString().split("T")[0];
    const currentTimeString = currentDate.toTimeString().split(" ")[0];

    const { start_date, end_date, start_time, end_time } = schedule;



    // Check if the current date is within the allowed date range
    if (currentDateString < start_date || currentDateString > end_date) {
        return false;
    }

    // Check if the current time is within the allowed time range
    if (start_time > end_time) // in different days
    {
        if (currentTimeString < start_time && currentTimeString > end_time)
        {
            return false;
        }
    }
    else // in same day
    {
        if (currentTimeString < start_time || currentTimeString > end_time)
        {
            return false;
        }
    }

    return true;
}

// fetching media data
function fetchMedia() {
    storedMedia = JSON.parse(localStorage.getItem("mediaItems")) || {};
    playlists = JSON.parse(localStorage.getItem("devicePlaylists")) || {};

    // bgPlaylistMusic = JSON.parse(localStorage.getItem("bgPlaylistMusic")) || {}; //uncomment for background music
}


// to handle moving to next item
//if finish the media items of current playlist then move to next playlist
// else move to next media item of current playlist
function handleMoveToNextItem(){
    if (currentIndex >= mediaData.length)
        {
            currentPlaylistId++;
                if (currentPlaylistId > playlistData.length - 1) {
                    currentPlaylistId = 0;
                }
                currentIndex = 0;
                playPlaylist();
        }
        else{

            startNextMediaItem();
        }
}

// start next item
function startNextMediaItem() {


    // if (currentIndex >= mediaData.length) {
    //     return;
    // }

    const mediaItem = mediaData[currentIndex];

    let item;

    // check what is the type of media and create item( video or image html element) to set it to player div
    if (isImage(mediaItem.type)) {
        item = `<img  class="object-contain animate__animated animate__${playlistData[currentPlaylistId].translation_animation} " src="${storedMedia[mediaItem.slug].url
            }" alt="Image">`;
    } else if (isVideo(mediaItem.type))
    {
        item = `<video class="animate__animated animate__${playlistData[currentPlaylistId].translation_animation}"    id="player-video"  ${currentAllowAutoplay == false ? "muted " : ""}   ><source src="${storedMedia[mediaItem.slug].url
            }" type="video/mp4">Your browser does not support the video tag.</video>`;
    }

    player.innerHTML = item; //set item to player div

    const currentItem = document.getElementById("player-video");

    // if item is video then play it  and set on ended event to go to next item
    // on catch error then move to next item
    if (isVideo(mediaItem.type) && currentItem) {
        console.log(mediaItem);
        currentAllowAutoplay?currentItem.muted=true:"";
        currentItem.play().then(() => {
                currentItem.onended = () => {
                        currentIndex++;
                        handleMoveToNextItem();
                };
                // currentItem.classList.remove('hidden');
            currentAllowAutoplay&&mediaItem.mute==false?currentItem.muted=false:"";
            })
            .catch((error) => {

                currentIndex++;
                handleMoveToNextItem();
            });
    }
    //if image then set event timeout to go to next item
    else {
        currentTimeout = setTimeout(() => {
            currentIndex++;

            handleMoveToNextItem();
        }, mediaItem.duration * 1000);
    }
}

// disable playlist if out of schedule or ....
function setDisablePlaylist(index) {
    playlistData[index].enable = false;
}

// Function to count the number of true 'enable' values
function countEnabledPlaylists(playlists) {
    return playlists.filter((playlist) => playlist.enable).length;
}

function playPlaylist() {

    //if there is not enable playlists then set standby screen
    if (countEnabledPlaylists(playlistData) == 0)
        standByScreen();


    else if(playlistData[currentPlaylistId].enable==false)
    {
        currentPlaylistId+=1;
        if(currentPlaylistId==playlistData.length)currentPlaylistId=0;
        playPlaylist();
    }

    // else playing current playlist
    else {
        mediaData = playlistData[currentPlaylistId].media;//get media of current playlist



        // handle schedule
        //if there is schedule then check if playlist in range of schedule

        if (playlistData[currentPlaylistId].allow_schedule)
        {
            // if playlist inside range of schedule
            if (isPlaylistScheduleAllowed(playlistData[currentPlaylistId].schedule))
            {

                startNextMediaItem();
                // startBgMusicPlayer();//uncomment for background music
            }

            // if out of range
            else
            {
                // disable the current  playlist
                setDisablePlaylist(currentPlaylistId);

                // move to next playlist

                currentPlaylistId++;

                if (currentPlaylistId >countEnabledPlaylists(playlistData) - 1) {
                    currentPlaylistId = 0;
                }

                currentIndex = 0;
                playPlaylist();
            }
        }

        // if there is not schedule for playlist then play it normally
        else
        {

            startNextMediaItem();
            // startBgMusicPlayer();//uncomment for background music
        }




    } //disable to test without schedule
}

// // play bg music for playlist
// function startBgMusicPlayer() {
//     if (playlistData[currentPlaylistId].allow_bg_music) {
//         if (currentAllowAutoplay) {
//             bg_music_audio.muted = true;
//             bg_music_audio.src =
//                 bgPlaylistMusic[playlistData[currentPlaylistId].slug].url;

//             bg_music_audio.play();
//             bg_music_audio.muted = false;
//         }
//     }
//     else{
//         bg_music_audio.src =null;
//         bg_music_audio.pause();
//     }
// }  //uncomment for background music

//check if playlsits do not have any media
function hasNoMedia(playlists) {
    return playlists.every(playlist => playlist.media.length === 0);
  }

// initial owl carousel for playling playlists
// Initial function to start playing the media items
async function initializePlayer() {


        currentIndex = 0;
        currentPlaylistId = 0; // Start from the first item
        if (currentTimeout) {//clear timeout
            clearTimeout(currentTimeout);
        }


        playPlaylist(); //play first playlist


    // startBgMusicPlayer();
}

// put all media items of playlist in one array to handle play it
async function initalData() {
    mediaData = [];
    playlistData = [];

    Object.entries(playlists).forEach(([playlistSlug, playlistDetails]) => {
        playlistData.push(playlistDetails);
        // playlistDetails.media.forEach(mediaItem => {
        //     mediaData.push(mediaItem);
        // });
    });

}

// check if media item is image
function isImage(mimeType) {
    return mimeType.startsWith("image/");
}
// check if media item is video
function isVideo(mimeType) {
    return mimeType.startsWith("video/");
}

// set standby screen =>if(not have playlists) or .....
function standByScreen() {
    player.innerHTML = `there is no playlists added`;
}

// check if current playlist inside or outside schedule if enable
function startCheckScheduleEvent() {

    clearTimeout(currentScheduleTimeout);
    console.log('start checking');
    playlistData.forEach((playlistItem,index) => {
        if(playlistItem.allow_schedule)
        {
            if (isPlaylistScheduleAllowed(playlistItem.schedule) == false) {
                console.log('playlist out of schedule');
                playlistData[index].enable=false;
                // if(index==currentPlaylistId)
                // {
                //     currentPlaylistId+=1;
                //     if(currentPlaylistId==playlistData.length)currentPlaylistId=0;
                //     console.log(currentPlaylistId,playlistData.length);
                //     playPlaylist();
                // }
            }
            else if(isPlaylistScheduleAllowed(playlistItem.schedule) == true)
            {
                console.log('playlist in schedule');



            //    check count of  current enable playlists for playing
              let countEnablePlaylists=countEnabledPlaylists(playlistData);


                playlistData[index].enable=true;
                console.log(currentPlaylistId,index);
               //the current count of enabled playlists before update=0 (standby screen) then re run the playlists
                if(countEnablePlaylists==0)
                {

                    playPlaylist();
                }
            }
        }
    });
    currentScheduleTimeout=setTimeout(() => {
        startCheckScheduleEvent();
    }, 60 * 1000);
}

// set if allow autoplay or not
function setAutoPlay() {

  try {
    Android.CheckUserGesture();
    currentAllowAutoplay=true;
    player.innerHTML='checking user gesture';
  } catch (error) {
    currentAllowAutoplay=false;
  }



}

// start playlists on screen
async function startPlaylistsOnScreen() {


    fetchMedia();

    //  check if browser support autoplay video or not
    setAutoPlay();

    let playlistKeys = Object.keys(playlists); // to check if there is playlists or not

    // stand by screen
    if (playlistKeys.length == 0) {
        standByScreen();
    }
    else
    {
        await initalData();

        // if there is no media then show standby screen
        if (hasNoMedia(playlistData)) {
            standByScreen();

        } else { //else start the playlist
            initializePlayer();
            startCheckScheduleEvent();
        }

    }


}
