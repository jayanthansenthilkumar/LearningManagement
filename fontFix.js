$(document).ready(function() {
    // Remove any font-family styles from links and use Poppins from our global CSS
    $('.btn-outline-primary, .nav-link').each(function() {
        $(this).css('font-family', '');
    });
    
    // Add this to dynamically created elements
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('[style*="font-family"]').length > 0) {
            $(e.target).find('[style*="font-family"]').css('font-family', '');
        }
    });
});
