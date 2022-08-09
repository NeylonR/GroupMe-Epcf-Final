const getRoleImg = document.querySelectorAll('.role img');

getRoleImg.forEach(roleImg => {
    roleImg.addEventListener('click', (e)=>{
        // selection de la div qui regroupe la div 'role' et 'jobContainer' de la carte lié à l'icône sur laquelle le visiteur a cliqué
        const getPlayerJobContainer = e.target.parentElement.parentElement;

        // selection de la div jobContainer qui correspond au role de l'icone qui reçoit le clic
        const getJobDiv = getPlayerJobContainer.querySelector(`.jobContainer[data-role=${e.target.dataset.role}]`);

        // selection de toute les div jobContainer qui sont visible (normalement une seule l'est)
        const getOpenedJobDiv = document.querySelectorAll('.jobContainer[data-status="visible"]');

        // la bonne div JobContainer passe en visible si elle était caché, si elle était visible elle sera fermé par une autre boucle 
        if(getJobDiv.dataset.status === 'hidden'){
            getJobDiv.dataset.status = 'visible';
        }

        // chaque div JobContainer passe en hidden
        getOpenedJobDiv.forEach(e => {
            e.dataset.status = 'hidden';
        });
    })
});

const getFilterDeploy = document.querySelector('.filterDeploy');
const getFilterDiv = document.querySelector('.filter');
getFilterDiv.addEventListener('click', ()=>{
    // if(getFilterDiv.dataset.size == 'shrink'){
    //     return getFilterDiv.dataset.size = 'grow';
    // }
    // getFilterDiv.dataset.size = 'shrink';
    return getFilterDiv.dataset.size = 'grow';
})

// bouton pour reinitialiser le filtre
const getFilterInput = document.querySelectorAll('.filterForm input');
const getFilterInputInt = document.querySelector('.filterForm input[type=number]');
const getFilterSelect = document.querySelectorAll('.filterForm select');
const getResetButton = document.querySelector('.filterForm .reset');
// lors du clique sur le bouton de reset toute les checkbox se décoche, l'input number se vide et les select retourne sur la valeur par défaut
getResetButton.addEventListener('click', () => {
    getFilterInput.forEach(input => {
        input.checked = false;
    });
    getFilterInputInt.value = '';
    getFilterSelect.forEach(input => {
        input.selectedIndex = 0;
    });
})
