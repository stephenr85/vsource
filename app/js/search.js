$(document).ready(function() {
            youtubeApiCall();
            youtubeApiCalltwo();
                   });
        

function youtubeApiCall(){
    $.ajax({
        cache: false,
        data: $.extend({
            key: 'AIzaSyAEyLPdX3kvIkGbetsf95OI5IqmQR4jOFc',
            playlistId: 'PLH-nnUXtAYzpIJxKuLmldQ8EW0Kyu6dSL',
            relevanceLanguage: 'en',
            type: 'video',
            part: 'snippet'
        }, {maxResults:30,pageToken:$("#pageToken").val()}),
        dataType: 'json',
        type: 'GET',
        timeout: 5000,
        url: 'https://www.googleapis.com/youtube/v3/playlistItems'
    })
    .done(function(data) {
        if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
        if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
        var items = data.items, videoList = "";
        $("#pageTokenNext").val(data.nextPageToken);
        $("#pageTokenPrev").val(data.prevPageToken);
        $.each(items, function(index,e) {
            videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
        });
        $("#hyv-watch-related").html(videoList);
        // JSON Responce to display for user
        new PrettyJSON.view.Node({ 
            el:$(".hyv-watch-sidebar-body"), 
            data:data
        });
    });
}

function youtubeApiCalltwo(){
    $.ajax({
        cache: false,
        data: $.extend({
            key: 'AIzaSyAEyLPdX3kvIkGbetsf95OI5IqmQR4jOFc',
            playlistId: 'PLH-nnUXtAYzp7UDj29arg6RlyLDuJ-cEa',
            relevanceLanguage: 'en',
            type: 'video',
            part: 'snippet'
        }, {maxResults:30,pageToken:$("#pageToken").val()}),
        dataType: 'json',
        type: 'GET',
        timeout: 5000,
        url: 'https://www.googleapis.com/youtube/v3/playlistItems'
    })
    .done(function(data) {
        if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
        if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
        var items = data.items, videoList = "";
        $("#pageTokenNext").val(data.nextPageToken);
        $("#pageTokenPrev").val(data.prevPageToken);
        $.each(items, function(index,e) {
 videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></td><td style="padding-left:10px; padding-top: 5px;"  vAlign="top">'+e.snippet.title+'</td></tr><tr class="spacer"></tr>';
        });
        $("#hyv-global-related").html(videoList);
        // JSON Responce to display for user
        new PrettyJSON.view.Node({ 
            el:$(".hyv-watch-sidebar-body"), 
            data:data
        });
    });
}



