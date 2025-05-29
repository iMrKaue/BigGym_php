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

const comprarBtns = document.querySelectorAll('.produto-btn');
comprarBtns.forEach(btn => {
    if(btn.textContent.trim() === 'Comprar') {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = btn.parentElement;
            const nome = card.querySelector('.produto-nome').textContent;
            const preco = parseFloat((card.querySelector('.produto-preco-atual')||{textContent:'0'}).textContent.replace('R$','').replace(',','.'));
            const img = card.querySelector('img').getAttribute('src');
            const subtituloEl = card.querySelector('div[style*="font-size:0.98rem"]');
            const subtitulo = subtituloEl ? subtituloEl.textContent : '';
            let carrinho = getCarrinho();
            let found = carrinho.find(item => item.nome === nome && item.preco === preco);
            if(found) found.quantidade += 1;
            else carrinho.push({nome, preco, img, subtitulo, quantidade:1});
            setCarrinho(carrinho);
        });
    }
});
