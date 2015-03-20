$(document).ready(function() {
$(".left_fb").hover(
  function () {
    $(this).stop().animate({left:'0px'}, 500);
  },
  function () {
       $(this).stop().animate({left:'-296px'}, 500);

  }
);

$(".right_fb").hover(
  function () {
    $(this).stop().animate({right:'0px'}, 500);
  },
  function () {
       $(this).stop().animate({right:'-296px'}, 500);

  }
);
});