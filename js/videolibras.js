/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {

    $('body').on('mousedown', '.move', function () {
        $(this).parents('.mobile').addClass('draggable').parents().on('mousemove', function (e) {
            $('.draggable').offset({
                top: e.pageY - $('.draggable').outerHeight() / 2,
                left: e.pageX - $('.draggable').outerWidth() / 2
            }).on('mouseup', function () {
                $(this).removeClass('draggable');
            });
        });
    }).on('mouseup', function () {
        $('.draggable').removeClass('draggable');
    });

    var mediaPlayer = $('#videotag').get(0);

    mediaPlayer.addEventListener('ended', function(e)
    {
      mediaPlayer.currentTime = 1;
      mediaPlayer.pause();
      $("#playPause").children().removeClass('fa-pause');
      $("#playPause").children().addClass('fa-play');

    }, false);

    $('#playPause').click(function () {
        if (mediaPlayer.paused)
        {
            mediaPlayer.play();
            $(this).children().removeClass('fa-play');
            $(this).children().addClass('fa-pause');
        }
        else
        {
            mediaPlayer.pause();
            $(this).children().removeClass('fa-pause');
            $(this).children().addClass('fa-play');
        }
    });

    $('#playBackSlider').on('change', function (ev) {
        mediaPlayer.playbackRate = ev.target.value;
    });

    $('body').on('click', '.hand', function (e) {
        e.preventDefault();
        $('#videotag').children('source').attr('src', $(this).attr('href'));

        mediaPlayer.load();
        play();

        $('#videodiv').show();
//        mediaPlayer.currentTime = 0;

        $('#videodiv').css("position", "absolute");

        $('#videodiv').css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop())-($('#videodiv').height()/2) + "px");
        $('#videodiv').css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft())-($('#videodiv').width()/2)-50 + "px");
    });

    $('body').on('click', '.cacooLink', function (e) {
        e.preventDefault();
        $('#videotag').children('source').attr('src', $(this).attr('href'));

        mediaPlayer.load();
        play();

        $('#cacoovideo').show();
        mediaPlayer.currentTime = 0;

        $('#cacoovideo').css("position", "absolute");

        $('#cacoovideo').css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop())-($('#cacoovideo').height()/2) + "px");
        $('#cacoovideo').css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft())-($('#cacoovideo').width()/2)-50 + "px");
    });

    $('body').on('click', '.tooltip_redhand', function (e) {

        $('#imagediv').find('img').attr('src', $(this).attr('rel'));
        $('#imagediv').show();
        $('#imagediv').css("top",e.pageY - 60);
        $('#imagediv').css("left",e.pageX + 30);
    });

    $('#replay').click(function () {
        mediaPlayer.currentTime = 0;
    });

    $('#faster').click(function () {
        mediaPlayer.playbackRate += 0.25;
    });

    $('.dismiss').click(function () {
        $(this).parents('.dissmissable').hide();
    });

    //CANVAS VIDEOS
    var outputCanvas = document.getElementById('output'),
            output = outputCanvas.getContext('2d'),
            bufferCanvas = document.getElementById('buffer'),
            buffer = bufferCanvas.getContext('2d'),
            video = document.getElementById('videotag'),
            width = outputCanvas.width,
            height = outputCanvas.height,
            interval;

    showOnce = true;
    function processFrame() {

        buffer.drawImage(video, 0, 0);

        if (showOnce){
            showOnce=false;
        }

        // this can be done without alphaData, except in Firefox which doesn't like it when image is bigger than the canvas
        var image = buffer.getImageData(0, 0, width, height),
                imageData = image.data,
                alphaData = buffer.getImageData(0, height, width, height).data;

        for (var i = 3, len = imageData.length; i < len; i = i + 4) {
            imageData[i] = alphaData[i - 1];
        }

        output.putImageData(image, 0, 0, 0, 0, width, height);
    }

    function play() {
        clearInterval(interval);
        interval = setInterval(processFrame, 40);
    };
//   fim do CANVAS VIDEOS

});
