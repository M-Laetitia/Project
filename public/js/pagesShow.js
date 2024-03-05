 gsap.from('#header', { opacity: 0, duration: 1.2, delay: 0.6 });  

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

// display the header with a delay 
/* setTimeout(function() {
$('#header').css('opacity','1');}, 400); */ 