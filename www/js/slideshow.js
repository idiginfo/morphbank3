$(function(){
    $('.fadelinks > :gt(0)').hide();
    setInterval(function(){
        $('.fadelinks > :first-child').fadeOut()
            .next().fadeIn().end().appendTo('.fadelinks');}, 3000);
});