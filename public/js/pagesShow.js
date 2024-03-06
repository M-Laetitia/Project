
// ^ ANIMATION FOR DETAIL (SHOW) PAGE
console.log("hello test")
if (document.getElementById('event-show')) {
    
    gsap.from('#header', { opacity: 0, duration: 1.2, delay: 0.6 });  
    
    if (document.getElementById('modules-workshop')) {
        var modules = document.getElementById('modules-workshop');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const detailsEvent = document.getElementById('details-event');
        const quote = document.getElementById('quote');
        const quoteText = document.getElementById('quote-text');
            
        // Function to check if an element is visible in the viewport
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight);
        }
    
        // Function to animate the appearance of #details-event
        function animateDetailsEvent() {
            // Check if 'details-event' is in the viewport
            if (isElementInViewport(detailsEvent)) {
                // Use GSAP to animate the appearance of 'details-event' with sliding up and fading in
                gsap.fromTo(detailsEvent, { y: 100, opacity: 0 }, { y: 0, opacity: 1, duration: 1 });
    
                // Use GSAP to animate the appearance of 'modules' with sliding up and fading in
                if (modules) {
                    gsap.fromTo(modules, { y: 100, opacity: 0 }, { y: 0, opacity: 1, duration: 1, delay: 0.5 });
                }
    
                // Animation of the quote appearing with a slide-in from the right
                gsap.fromTo(quote, { x: window.innerWidth, opacity: 1 }, { x: 0, opacity: 1, duration: 1 });
                
                // Replace each letter of the text with a span element
                quoteText.innerHTML = quoteText.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
                const letters = quoteText.querySelectorAll('.letter');
                
                // Animate each letter of the text
                gsap.from(letters, {
                        opacity: 0,
                        y: 20,
                        duration: 0.3,
                        stagger: 0.02, // Interval between each letter animation
                        delay: 1 // Delay the animation by 0.5 seconds
                });
    
                // Remove the scroll event listener after 'details-event' is visible
                window.removeEventListener('scroll', animateDetailsEvent);
            }
        }
        
        // Add event listener to detect scrolling and trigger animation
        window.addEventListener('scroll', animateDetailsEvent);
    
        // Trigger the animation on page load after a delay of 1 second
        setTimeout(animateDetailsEvent, 1000);
    });
}


// ^ ANIMATION FOR LIST (INDEX) PAGE

if (document.getElementById('events-index')) {
    var eventsIndex = document.getElementById('events-index');

    document.addEventListener('DOMContentLoaded', function() {
        // Function to check if an element is visible in the viewport
        function isElementInViewport(el) {
            // Retrieve the bounding rectangle of the element
            var rect = el.getBoundingClientRect();
            // Check if the element's top, left, bottom, and right coordinates are within the viewport
            return rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && rect.right <= (window.innerWidth || document.documentElement.clientWidth);
        }

        // Function to check and show visible events
        function checkAndShowEvents() {
            // Remove the "underscore" class from all spans
        document.querySelectorAll('.container-event span').forEach(function(span) {
            span.classList.remove('underscore');
        });

            // Select all elements with class 'container-event' and loop through them
            document.querySelectorAll('.container-event').forEach(function(container) {
                // Check if the current container is in the viewport
                if (isElementInViewport(container)) {
                    // change the opacity and make the container visible
                    container.style.opacity = '1';

                    // Add the "underscore" class to the span within the current container
                container.querySelector('span').classList.add('underscore');
                }
            });
        }

        // Event listener to detect user scrolling
        window.addEventListener('scroll', checkAndShowEvents);

        // Delay the initial check and display of events
        setTimeout(checkAndShowEvents, 1000);
    });
}

// display the header with a delay 
/* setTimeout(function() {
$('#header').css('opacity','1');}, 400); */