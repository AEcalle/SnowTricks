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
        const step = moreData.dataset.step;

        fetch(url+id+index)
        .then(response => response.text())
        .then(data => {
            document.getElementById('more').innerHTML += data;
            const newIndex = parseInt(index) + parseInt(step);
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

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collection);

    const item = document.createElement('div');
    item.setAttribute('class','col-12 col-lg-3');

    item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
        /__name__/g,
        collectionHolder.dataset.index
      );
  
    collectionHolder.appendChild(item);
  
    collectionHolder.dataset.index++;

    collectionHolder
    .querySelectorAll('.remove_item_link')
    .forEach(btn => btn.addEventListener('click', removeFormToCollection));
    collectionHolder
    .querySelectorAll('input[type="file"]')
    .forEach(input => input.addEventListener('change', imagePreview));
    collectionHolder
    .querySelectorAll('.video input[type="text"]')
    .forEach(input => input.addEventListener('change', videoPreview));
  };

const removeFormToCollection = (e) => {
    e.currentTarget.closest('.item').parentElement.remove();
};

const removeImageLink = (e) => {
    e.currentTarget.closest('.item').remove();
};

const imagePreview = (e) => {
    console.log(e.currentTarget.files);
    const [file] = e.currentTarget.files
        if (file) {
            const img = document.createElement('img');
            img.setAttribute('src', URL.createObjectURL(file));
            img.setAttribute('class','img-fluid w-100 my-3');
            e.currentTarget.after(img);
        }
};

const videoPreview = (e) => {
    const input = e.currentTarget

    const video = document.createElement('iframe');
    video.setAttribute('src', input.value.replace('watch?v=','/embed/').replace('video','/embed/video').replace('vimeo.com','player.vimeo.com/video'));
    video.setAttribute('class','w-100 my-3');
    e.currentTarget.after(video);

};

document
    .querySelectorAll('.loadMoreButton')
    .forEach(btn => btn.addEventListener('click', loadMore));
document
    .querySelectorAll('.delete')
    .forEach(item => item.addEventListener('click', deleteConfirmation));
document
    .querySelectorAll('.add_item_link')
    .forEach(btn => btn.addEventListener('click', addFormToCollection));
document
    .querySelectorAll('.remove_image_link')
    .forEach(btn => btn.addEventListener('click', removeImageLink));

const hiddenImages = document.querySelectorAll('.image input[type="hidden"]');
const length = hiddenImages.length;
for (let i = 0; i<length ; i++) {
    const img = document.createElement('img');
    img.setAttribute('src','../build/images/'+hiddenImages[i].value);
    img.setAttribute('class','img-fluid w-100 my-3');
    hiddenImages[i].after(img);
}





