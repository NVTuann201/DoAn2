@extends('layouts.home')

@section('css')
@endsection

@section('content')
<home-component :quan_huyen="{{$quan_huyen}}"></home-component>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $(window).bind("scroll", function(e) {
            var top = $(window).scrollTop();
            if (top > 50) { //Khoảng cách đã đo được
                $(".stickyNav").addClass("hasOffset");
                console.log(1);
            } else {
                $(".stickyNav").removeClass("hasOffset");
            }
        });
                
        // css bg header
        var pathHeader = 'url(images/index/background-header/';
        var request = new XMLHttpRequest();
        request.open("GET", "/images/index/backgroud-header.json", false);
        request.send(null);
        var imagesArray = JSON.parse(request.responseText);        
        imageBackgroud = imagesArray[Math.floor(Math.random() * imagesArray.length)];
        if(imageBackgroud.name){
            urlBackgroudImage = pathHeader + imageBackgroud.name;
            $(".home__header").css('background-image', urlBackgroudImage);
            var htmlAuthor = '';
            if (imageBackgroud.author) {
                htmlAuthor = '<i>' + imageBackgroud.author + '</i>&nbsp;';
            }
            if (imageBackgroud.location) {
                htmlAuthor = imageBackgroud.location;
            }
            if (imageBackgroud.id && imageBackgroud.type) {               
                $("#author").attr('href', '/detail/' + imageBackgroud.type + '/' + imageBackgroud.protected_id);
            }            
            $("#author").html(htmlAuthor);        
        }               
    });

    function httpGetAsync(theUrl, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.responseText);
        }
        xmlHttp.open("GET", theUrl, true); // true for asynchronous
        xmlHttp.send(null);
    }

    function httpGetImageHeaderBackgroudRandom(){

    }
</script>
@endsection
