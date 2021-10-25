/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import 'bootstrap';

// or get all of the named exports for further usage
import * as bootstrap from 'bootstrap';

// start the Stimulus application
import './bootstrap';

const loadMore = () => {
        const moreData = document.getElementById('moreData');

        const url = moreData.dataset.url;

        let id = '';
        if (moreData.dataset.id !== undefined) {
            id = moreData.dataset.id+'/';
        }

        const index = moreData.dataset.index;

        fetch(url+id+index)
        .then(response => response.text())
        .then(data => {
            document.getElementById('more').innerHTML += data;
            const newIndex = parseInt(index) *2;
            moreData.setAttribute('data-index', newIndex);
            const backToTopButton = document.getElementById('backToTopButton');
            if (backToTopButton !== null){
                backToTopButton.setAttribute('class','back-to-top d-flex align-items-center justify-content-center');
            }
        });
}

const deleteConfirmation = () => {
    return window.confirm('Comfirm you want to delete this trick.');
}

document.getElementById('loadMoreButton').addEventListener('click', loadMore);
const deletes = document.querySelectorAll('.delete');
deletes.forEach(function(item){
    item.addEventListener('click', deleteConfirmation);
}
);



