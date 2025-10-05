function createModal(eventname, photoData, fullName,orgName,post,device) {
    // Создаем модальное окно
    const modal = document.createElement('div');
    modal.className = 'modal';
    
    // Вычисляем позицию окна
    const leftPosition =  leftPhotoWindow + ((windowsCount%windowsCountsetings) * windowWidth);
    const topPosition = topPhotoWindow;
    // Устанавливаем позицию окна
    modal.style.left = `${leftPosition}px`;
    modal.style.top = `${topPosition}px`;
    // Создаем заголовок модального окна
    const header = document.createElement('div');
    header.className = 'modal-header';

    // Создаем полоску с надписью "Информация"
    const titleBar = document.createElement('div');
    titleBar.className = 'title-bar';
    const titleText = document.createElement('span');
    titleText.textContent = 'Информация';
    titleBar.appendChild(titleText);

    // Создаем кнопку закрытия
    const closeButton = document.createElement('button');
    closeButton.className = 'close-button';
    closeButton.textContent = '';
    
    const closeModal = () => {
        if (document.body.contains(modal)) {
            windowsCount--;
            document.body.removeChild(modal);
            closeButton.removeEventListener('click', closeModal);
            makeModalDraggable(modal);
        }
    };
    
    closeButton.addEventListener('click', closeModal);

    // Добавляем кнопку закрытия в заголовок
    header.appendChild(titleBar);
    header.appendChild(closeButton);

    // Добавляем заголовок в модальное окно
    modal.appendChild(header);

    // Добавляем блок название события
    const statusBlock = document.createElement('div');
    statusBlock.className = 'status-block';
    statusBlock.style.padding = '10px';
    const statusText = document.createElement('span');
    statusText.textContent = eventname;
    statusBlock.appendChild(statusText);

    // Добавляем блок точка прохода (new)
    const pointPassageBlock = document.createElement('div');
    pointPassageBlock.className = 'full-name-container';
    const pointPassageText = document.createElement('span');
    pointPassageText.textContent = device;
    pointPassageBlock.appendChild(pointPassageText);

    // // Добавляем место для уменьшенной фотографии
    const photo = document.createElement('img');
    photo.src = "data:image/png;base64, " + photoData;
    photo.style.width = '200px'; 
    photo.style.height = 'auto'; 
    photo.style.padding = '5px';
    const photoContainer = document.createElement('span');
    photoContainer.appendChild(photo);
    document.body.appendChild(photoContainer);
    
    // Добавляем блок фио
    const fullNameContainer = document.createElement('div');
    fullNameContainer.className = 'full-name-container';
    const FIOText = document.createElement('span');
    FIOText.textContent = fullName;
    fullNameContainer.appendChild(FIOText);

    // Добавляем блок родительской орг (new)
    const parentOrgContainer = document.createElement('div');
    parentOrgContainer.className = 'full-name-container';
    const parentOrgText = document.createElement('span');
    parentOrgText.textContent = orgName;
    parentOrgContainer.appendChild(parentOrgText);


    // Добавляем блок должность (new)
    const postContainer = document.createElement('div');
    postContainer.className = 'full-name-container';
    const postText = document.createElement('span');
    postText.textContent = post;
    postContainer.appendChild(postText);


    modal.appendChild(statusBlock); //блок статуса
    modal.appendChild(pointPassageBlock); //точка прохожа
    modal.appendChild(photoContainer); //место для фотографии
    modal.appendChild(fullNameContainer); //поля для ФИО
    modal.appendChild(parentOrgContainer); //род орг
    modal.appendChild(postContainer); //должность

    // Добавляем модальное окно в тело документа
    document.body.appendChild(modal);

    // Делаем модальное окно перемещаемым
    makeModalDraggable(modal);
    
	modal.style.height =`${windowHeight}px`;
	modal.style.width =`${windowWidth}px`;
    // Показываем модальное окно
    modal.style.display = 'block';
    
    //console.log("Окно появилось!");


    setTimeout(() => {
        // Проверяем, не закрыто ли окно вручную
        if (document.body.contains(modal)) {
            closeModal(); // Закрываем модальное окно после таймаута, только если оно не было закрыто вручную
        }
    }, windowTimeout * 1000);
    windowsCount++;
}