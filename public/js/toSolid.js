$(document).ready(function() {
    // Transition effect for navbar
    if ($(window).width() < 768) {
        $(".navbar").addClass("rwd");
    }
    $(window).scroll(function() {
        // checks if window is scrolled more than 500px, adds/removes solid class
        if ($(window).width() < 768) {
            // $(".navbar").addClass("rwd");
            if ($(this).scrollTop() > 200) {
                $(".navbar").removeClass("rwd");
                $(".navbar").addClass("solid-sm");
            } else {
                $(".navbar").removeClass("solid-sm");
                $(".navbar").addClass("rwd");
            }
        } else {
            if ($(this).scrollTop() > 200) {
                $(".navbar").addClass("solid");
            } else {
                $(".navbar").removeClass("solid");
            }
        }
        
    });
});

// $(document).ready(function () {

//     const w = $(window).width();
//     if (w < 768) {
//         $(".navbar").addClass("rwd");
//     }
// });
