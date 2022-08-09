const getUploadButton = document.getElementById('uploadButton');
const getUploadInput = getUploadButton.querySelector('input');
const getPInput = getUploadButton.querySelector('p');

getUploadInput.addEventListener('change', (e)=>{
    getPInput.innerText = `Fichier :  ${e.target.files[0].name}`;
})