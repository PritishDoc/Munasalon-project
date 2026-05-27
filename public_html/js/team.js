/* ============================================
   Team Section – See More / Show Less
   js/team.js  |  v1.0.0
   ============================================ */
'use strict';

(function() {
    function initTeamSeeMore() {
        const teamBtn = document.getElementById('see-more-team-btn');
        if (!teamBtn) return;
        
        // Prevent duplicate listeners
        if (teamBtn.dataset.teamBound) return;
        teamBtn.dataset.teamBound = 'true';
        
        const hiddenTeam = document.querySelectorAll('.team-card.team-hidden');
        let isExpanded = false;
        const section = teamBtn.closest('section');
        
        teamBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            teamBtn.classList.add('loading');
            teamBtn.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
            teamBtn.disabled = true;
            
            setTimeout(() => {
                if (!isExpanded) {
                    // Show hidden team members with staggered animation
                    hiddenTeam.forEach((item, index) => {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.classList.add('team-show-animation');
                        }, index * 50);
                    });
                    
                    teamBtn.innerHTML = 'Show Less Team <i class="fas fa-arrow-up"></i>';
                    teamBtn.style.border = '1px solid var(--clr-gold)';
                    teamBtn.style.color = 'var(--clr-gold)';
                    isExpanded = true;
                } else {
                    // Hide team members
                    const visibleHidden = document.querySelectorAll('.team-card.team-hidden');
                    visibleHidden.forEach((item, index) => {
                        item.classList.remove('team-show-animation');
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 150);
                    });
                    
                    teamBtn.innerHTML = 'See More Team <i class="fas fa-arrow-down"></i>';
                    teamBtn.style.border = '1px solid #333';
                    teamBtn.style.color = 'var(--clr-white)';
                    isExpanded = false;
                    
                    if (section) {
                        setTimeout(() => {
                            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);
                    }
                }
                
                teamBtn.classList.remove('loading');
                teamBtn.disabled = false;
            }, 200);
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTeamSeeMore);
    } else {
        initTeamSeeMore();
    }
})();