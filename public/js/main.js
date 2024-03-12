// ^ Make add flash message disappear after 5s

if(document.querySelector('.alter')) {
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').slideUp(3000); // 1000 millisecondes for the animation's duration
        }, 2000); // delay
    });
}


// ^ Artist page Carousel 

document.addEventListener('DOMContentLoaded', function() {

    const track = document.querySelector('.track')

    if (track) {

        const slides = Array.from(track.children)
        // console.log(slides)
        const nextBtn = document.querySelector('.button--right')
        const prevBtn = document.querySelector('.button--left')
        
        const dotsNav = document.querySelector('.nav')
        const dots = Array.from(dotsNav.children)
        
        // const slideSize = slides[0].getBoundingClientRect();
        // const slideWidth = slideSize.width;
        // Retrieve the width of the first slide by obtaining its bounding rectangle dimensions.
        const slideWidth = slides[0].getBoundingClientRect().width;
        // console.log(slideWidth)
        
        // ~ arrange the slides next to one another
        // slides[0].style.left = slideWidth *0  + 'px';
        // slides[1].style.left = slideWidth *1  + 'px';
        // slides[2].style.left = slideWidth *2  + 'px';
        const setSlidePosition = (slide, i) => {
            slide.style.left = slideWidth * i + 'px'
        }
        
        slides.forEach(setSlidePosition)
        
        // ~ When I click right, move slides to the right
        // nextBtn.addEventListener('click', e => {
        //     const currentSlide = track.querySelector('.current-slide')
        //     // console.log(currentSlide.nextElementSibling)
        //     const nextSlide = currentSlide.nextElementSibling
        //     const amountToMove = nextSlide.style.left
        //     //move to the next slide
        //     track.style.transform = 'translateX(-' + amountToMove + ')'
        //     // no dot when using classList
        //     currentSlide.classList.remove('current-slide')
        //     nextSlide.classList.add('current-slide')
        // })
        
        const moveSlide = (track, currentSlide, targetSlide) => {
            track.style.transform = 'translateX(-' + targetSlide.style.left + ')'
            currentSlide.classList.remove('current-slide')
            targetSlide.classList.add('current-slide')
        }
        
        const updateDots = (currentDot, targetDot) => {
            currentDot.classList.remove('current-slide')
            targetDot.classList.add('current-slide')
        }
        
        // ~ hide or show last arrows
        const hideShowArrows = (slides, prevBtn, nextBtn, targetIndex) => {
            if(targetIndex === 0 ) {
                prevBtn.classList.add('is-hidden')
                nextBtn.classList.remove('is-hidden')
            } else if (targetIndex === slides.length - 1 ) {
                prevBtn.classList.remove('is-hidden')
                nextBtn.classList.add('is-hidden')
            } else {
                prevBtn.classList.remove('is-hidden')
                nextBtn.classList.remove('is-hidden')
            }
        }
        
        // ~ When I click left, move slides to the left
        prevBtn.addEventListener('click', e=> {
            const currentSlide = track.querySelector('.current-slide')
            const prevSlide = currentSlide.previousElementSibling
            
            const currentDot = dotsNav.querySelector('.current-slide')
            const prevDot = currentDot.previousElementSibling
        
            const prevIndex = slides.findIndex(slide => slide === prevSlide)
            
            moveSlide(track, currentSlide, prevSlide)
            updateDots(currentDot, prevDot)
            hideShowArrows(slides, prevBtn, nextBtn, prevIndex)
            
        });
        
        // ~ When I click right, move slides to the right
        nextBtn.addEventListener('click', e => {
            const currentSlide = track.querySelector('.current-slide')
            const nextSlide = currentSlide.nextElementSibling
        
            const currentDot = dotsNav.querySelector('.current-slide')
            const nextDot = currentDot.nextElementSibling
        
            const nextIndex = slides.findIndex(slide => slide === nextSlide)
        
            moveSlide(track, currentSlide, nextSlide)
            updateDots(currentDot, nextDot)
            hideShowArrows(slides, prevBtn, nextBtn, nextIndex)
        });
        
        // ~ When I click the nav indicators, move to that slide
        
        dotsNav.addEventListener('click', e => {
            // what indicator was cliked on?
            const targetDot = e.target.closest('button');
            if (!targetDot) return
        
            const currentSlide = track.querySelector('.current-slide')
            const currentDot = dotsNav.querySelector('.current-slide')
            // get the index of the dot clicked
            const targetIndex = dots.findIndex(dot => dot === targetDot )
            const targetSlide = slides[targetIndex];
        
            moveSlide(track, currentSlide, targetSlide)
            updateDots(currentDot, targetDot)
            hideShowArrows(slides, prevBtn, nextBtn, targetIndex)
        
        })
    }
});


// ^ Artists page - eyes

if (document.querySelector('.ri-eye-close-fill')) {

    $(document).ready(function() {
        // console.log("test icon")
    
        $('.ri-eye-close-fill').hover(
            function() {
                $(this).toggleClass('ri-eye-fill')
            },
        )
        
    })

}

// // ^ Artists page - eyes
// $(document).ready(function() {
//     $('.ri-eye-close-fill').hover(
//         function() {
//             $(this).data('state', 'ri-eye-fill').animate({ opacity: 0 }, 200, function() {
//                 $(this).toggleClass('ri-eye-fill').animate({ opacity: 1 }, 600);
//             });
//         },

//     );
// });


// ^ Artist edit infos toggle
if (document.querySelector('edit-info-btn')) {

    $(document).ready(function() {
        $('.edit-info-btn').click(function(){
            $(".edit-infos").slideToggle();
        });
    })
}

// ^ see past events toggle

if (document.querySelector('past-event-btn')) {

    $(document).ready(function() {
        $('.past-event-btn').click(function(){
            $(".past-events").slideToggle();
        });
    
        $('.past-event-btn').hover(
            function() {
                $('.past-event-title h2').css('color', 'rgb(240, 189, 47)');
            },
            function() {
                $('.past-event-title h2').css('color', 'rgb(25, 24, 27)');
            }
        );
    })
}

// ^ DASHBOARD MAIN PAGE : 

$(document).ready(function() {

    // ^ confirmation delete poppup
    $('.delete-btn').click(function(){
        event.preventDefault(); 
        var targetId = $(this).data('target');
        console.log(targetId)
        $("#" + targetId).slideToggle();
    });

    $('.close-confirmation-btn').click(function(){
        var deleteContainer = $(this).closest('.delete-container');
        deleteContainer.slideToggle();
    })

    // ^ show/hide past event

    $('#arrow-past-event').click(function() {
        $('.past-events-container').slideToggle();
    })

    $('#arrow-past-expo').click(function() {
        $('.past-expos-container').slideToggle();
    })

    $('#arrow-past-workshop').click(function() {
        $('.past-workshops-container').slideToggle();
    })

})

// ^^ 
$(document).ready(function(){
    console.log("hi");
    $("#voirDispo").click(function(event){
        event.preventDefault();
        console.log("hello");
        
        // Récupérer les valeurs sélectionnées de la date et du studio
        var selectedDate = $("#date").val();
        var selectedStudio = $("#studio option:selected").attr("id"); // Récupérer l'ID du studio sélectionné
        console.log("dateselectted", selectedDate);
        console.log("studuiselectted", selectedStudio);

        var link = $(this).attr("href");
        // Séparer l'URL en parties
        var parts = link.split('/');

        // Trouver l'index de "dashboard/"
        var dashboardIndex = parts.indexOf('dashboard');

        // Remplacer les valeurs de studioID et selectedDate
        parts[dashboardIndex + 1] = selectedStudio; // studioID
        parts[dashboardIndex + 2] = selectedDate;   // selectedDate

        // Reconstruire l'URL
        link = parts.join('/');
        console.log("link02", link);

        // Rediriger vers la page avec le lien mis à jour
        window.location.href = link;
    });
});


//&---------------------------------
//&  ███████  ██████  ██████  ███    ███ 
//~~ ██      ██    ██ ██   ██ ████  ████ 
//~~ █████   ██    ██ ██████  ██ ████ ██ 
//?  ██      ██    ██ ██   ██ ██  ██  ██ 
//?  ██       ██████  ██   ██ ██      ██
//? ---------------------------------


if(document.querySelectorAll('#form-register')) {

    // elements animation
    // gsap.from(".register-user", {
    //     opacity: 0, 
    //     duration: 1.2, 
    //     ease: "easeInOut", 
    //     delay: 0.8
    // });

    // gsap.from("#separator", {
    //     x : 100,
    //     opacity: 0, 
    //     duration: 1.6, 
    //     ease: "easeInOut", 
    //     delay: 2
    // });

    // gsap.from(".left-register", {
    //     opacity: 0, 
    //     duration: 1.6, 
    //     ease: "easeInOut", 
    //     delay: 1.5
    // });

    // gsap.from(".right-register", {
    //     opacity: 0, 
    //     duration: 1.6, 
    //     ease: "easeInOut", 
    //     delay: 1.7
    // });

    // add color when focus on input
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('#form-register input');
        
        inputs.forEach(function(input) {
            if (!input.matches('#terms-register input')) {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#f0bd2f'; 
                    this.previousElementSibling.style.color = '#f0bd2f'; 
                });
        
                //blur event is triggered when the input field loses focus
                input.addEventListener('blur', function() {
                    this.style.borderColor = '#939393'; 
                    this.previousElementSibling.style.color = ''; 
                });
            }
        });
    });

}

