    // Remove 'selected' class from all status badges
    const badges = button.parentElement.querySelectorAll('.badge');
    badges.forEach(badge => badge.classList.remove('selected'));
    
    // Add 'selected' class to clicked button
    button.classList.add('selected');
