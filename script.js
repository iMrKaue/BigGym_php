function showInfo(sectionId) {
    const sections = document.querySelectorAll('.dropdown-info');
    sections.forEach(section => {
        section.style.display = section.id === sectionId ? 'block' : 'none';
    });
}

function showSection(sectionId) {
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.style.display = section.id === sectionId ? 'block' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.servico').forEach(servico => {
        servico.addEventListener('mouseenter', function() {
            servico.querySelector('.extra-options').style.display = 'block';
        });

        servico.addEventListener('mouseleave', function() {
            servico.querySelector('.extra-options').style.display = 'none';
        });
    });
});

window.onload = function() {
    showSection('home');
    showInfo('missao'); 
};
