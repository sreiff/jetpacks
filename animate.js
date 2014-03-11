 //source: http://stackoverflow.com/questions/10551380/how-to-make-an-image-move-in-a-circular-path-using-jquery
 
 $(document).ready(function() {
    moveit();
    });
 
 
var t = 0;

function moveit() {
    t += 0.03;

    var r = 100;         // radius
    var xcenter = 530;   // center X position
    var ycenter = 600;   // center Y position

    var newLeft = Math.floor(xcenter + (r * Math.cos(t)));
    var newTop = Math.floor(ycenter + (r * Math.sin(t)));

    $('#friends').animate({
        top: newTop,
        left: newLeft,
    }, 1, function() {
        moveit();
    });
}

