
var player = document.getElementById("main");

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function deleteAllCookies() {
    player.innerHTML="delete cookies..";
    const cookies = document.cookie.split("; ");
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i];
      const eqPos = cookie.indexOf("=");
      const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
      deleteCookie(name);
    }
  }
  function deleteCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
  }




// function storeBgPlaylistMusic(slug,bgMusicItem){
//     const storedBgPlaylistMusic = JSON.parse(localStorage.getItem('bgPlaylistMusic')) || {};
//     storedBgPlaylistMusic[slug] = bgMusicItem;
//     localStorage.setItem('bgPlaylistMusic', JSON.stringify(storedBgPlaylistMusic));
// }

// store new media item
function storeMedia(slug, mediaItem,storageName) {
    const storedMedia = JSON.parse(localStorage.getItem(storageName)) || {};

    storedMedia[slug] = mediaItem;
    localStorage.setItem(storageName, JSON.stringify(storedMedia));

}
//convert media
async function downloadAndConvertMedia(slug,path,storageName) {
    player.innerHTML='download media..';
    try {
        const response = await fetch(path);
        const blob = await response.blob();

        // Convert the blob to a web-friendly format if necessary
        // Here, we're assuming the media is already in a usable format
        const reader = new FileReader();

        return new Promise((resolve, reject) => {
            reader.onloadend = function() {
                const mediaItem = {
                    url: reader.result
                    // createdDate: createdDate
                };
                storeMedia(slug, mediaItem,storageName);
                resolve();  // Resolve the promise when the media is stored
            };

            reader.onerror = function(error) {
                reject(error);  // Reject the promise in case of error
            };

            reader.readAsDataURL(blob);
        });
    } catch (error) {
        console.error(`Error downloading media ${media.slug}:`, error);
    }
}

async function checkAndDeleteMediaItems(currentMediaCounts){
    const storedMedia = JSON.parse(localStorage.getItem('mediaItems')) || {};

    for (const storedSlug in storedMedia)
        {
            if(!currentMediaCounts[storedSlug])
            {
                delete storedMedia[storedSlug];
            }
        }

    localStorage.setItem('mediaItems', JSON.stringify(storedMedia));
}


// check if there is new media the store and convert it
async function checkAndDownloadMedia(playlists) {

    const storedMedia = JSON.parse(localStorage.getItem('mediaItems')) || {};
    // const storedBgMusicPlaylists=JSON.parse(localStorage.getItem('bgPlaylistMusic')) || {}; //uncomment for background music
    const currentMediaCounts = {};

    //for each playlist take it media items and check what is the media item need to store(if not stored previously in mediaItems) then download it and store by (downloadAndConvertMedia) function
    for (const playlist of playlists) {


        //uncomment for background music
        // if(playlist.bg_music_path)//check if there is bg music file for playlist
        // {
        //      if(!storedBgMusicPlaylists[playlist.slug])
        //         await downloadAndConvertMedia(playlist.slug,playlist.bg_music_path,'bgPlaylistMusic');
        // }

        for (const mediaItem of playlist.media)
        {
            currentMediaCounts[mediaItem.slug] = (currentMediaCounts[mediaItem.slug] || 0) + 1;

            if (!storedMedia[mediaItem.slug]) {
                await downloadAndConvertMedia(mediaItem.slug,mediaItem.path,'mediaItems');  // Wait for the download and conversion to finish
            }
        }
    }


     //check and clear media stored  items that are not avilable now


      if(playlists.length>0)
        {
        // Remove media items that are not in current media slugs
           await checkAndDeleteMediaItems(currentMediaCounts);


       }
}

// store playlists of deivce in local storage
async function storePlaylists(playlists){
    const NewPlaylists = {};
    console.log(playlists);
    playlists.forEach(playlist => {

         // Calculate total duration for the playlist
        const totalDuration = playlist.media.reduce((sum, media) => {
            return sum + media.pivot.duration;
        }, 0);

        NewPlaylists[playlist.slug] = {
            name: playlist.name,
            bg_music_path:playlist.bg_music_path,
            allow_bg_music:playlist.allow_bg_music,
            playlist_id:playlist.id,
            schedule:playlist.schedule,
            allow_schedule:playlist.allow_schedule,
            enable:true,
            slug:playlist.slug,
            translation_animation:playlist.translation_animation,
            total_duration: totalDuration, // Set the total duration here
            media: playlist.media.map(media => ({

                name: media.name,
                duration: media.pivot.duration,
                type: media.type,
                slug:media.slug,
                mute:media.pivot.mute,
                created_at:media.created_at,
            }))
        };
    });


    // Store the transformed data in localStorage
    localStorage.setItem('devicePlaylists', JSON.stringify(NewPlaylists));
    await checkAndDownloadMedia(playlists);


}
// get device playlists by device id
async function getPlaylistsDevice(deviceId) {


    try {
        const response = await fetch(`/device-player/${deviceId}/getcontent`);
        const data = await response.json();

        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null;
    }
}



