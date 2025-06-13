function makeModalDraggable(modal) {
    let isDragging = false;
    let offsetX, offsetY;

    // Обработчик начала перетаскивания
    function handleDragStart(e) {
        isDragging = true;
        offsetX = e.clientX - modal.getBoundingClientRect().left;
        offsetY = e.clientY - modal.getBoundingClientRect().top;
    }

    // Обработчик окончания перетаскивания
    function handleDragEnd() {
        isDragging = false;
    }

    // Обработчик движения мыши
    function handleDragMove(e) {
        if (isDragging) {
            modal.style.left = e.clientX - offsetX + 'px';
            modal.style.top = e.clientY - offsetY + 'px';
        }
    }

    // Добавляем обработчики событий
    modal.querySelector('.modal-header').addEventListener('mousedown', handleDragStart);
    window.addEventListener('mouseup', handleDragEnd);
    window.addEventListener('mousemove', handleDragMove);
}
function createSettingsModal2() {
   // loadSettings();
    
    const modal = document.createElement('div');
    modal.className = 'modal';
    const leftPosition = 100;
    const topPosition = 50;

    modal.style.left = `${leftPosition}px`;
    modal.style.top = `${topPosition}px`;

    const header = document.createElement('div');
    header.className = 'modal-header';

    const titleBar = document.createElement('div');
    titleBar.className = 'title-bar';
    const titleText = document.createElement('span');
    titleText.textContent = 'Настройки';
    titleBar.appendChild(titleText);

    const closeButton = document.createElement('button');
    closeButton.className = 'close-button';
    closeButton.textContent = '';


    const closeModal = () => {
		localStorage.setItem('windowsCountsetings', windowsCountsetings);
		windowsCount = windowsCountsetings;
		localStorage.setItem('leftPhotoWindow', leftPhotoWindow);
		localStorage.setItem('topPhotoWindow', topPhotoWindow);
		localStorage.setItem('windowTimeout', windowTimeout);
		localStorage.setItem('timeUpdate', timeUpdate);
        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
    };

    closeButton.addEventListener('click', closeModal);

    const tabs = document.createElement('div');
    tabs.className = 'tabs';

    const tabNames = ['General']; // Замените на нужные названия ваших вкладок

    const tabContents = {
		 'General': createTimerSettings(),
        // Добавьте больше вкладок и соответствующих функций для создания настроек
    };

    for (const tabName of Object.keys(tabContents)) {
        const tabButton = document.createElement('button');
        tabButton.textContent = tabName;
        tabButton.className = 'tab-button';
        tabButton.addEventListener('click', () => {
            switchTabs(tabButton, tabContents[tabName]);
        });
        tabs.appendChild(tabButton);
    }

    let activeTabContent = tabContents[tabNames[0]];

    header.appendChild(titleBar);
    header.appendChild(closeButton);   
    modal.appendChild(header);
    modal.appendChild(tabs);
    modal.appendChild(activeTabContent);

    document.body.appendChild(modal);

    makeModalDraggable(modal);

    modal.style.height = 'auto';
    modal.style.display = 'block';
}


function createTimerSettings(){
    const timerSettingsContainer = document.createElement('div');
    timerSettingsContainer.className = 'settings-container';
    const windowsCountsetingsInput = createInput('number', 'Количество окон', windowsCountsetings, function(newValue) {
        windowsCountsetings = newValue;
    });
    const leftPhotoWindowInput = createInput('number', 'Сдвиг слева', leftPhotoWindow, function(newValue) {
        leftPhotoWindow = newValue;
    });
    const topPhotoWindowInput = createInput('number', 'Сдвиг сверху', topPhotoWindow, function(newValue) {
        topPhotoWindow = newValue;
    });
	const WindowWidthInput = createInput('number', 'Ширина окна', windowWidth, function(newValue) {
        windowWidth = newValue;
    });
    const WindowHeightInput = createInput('number', 'Высота окна', windowHeight, function(newValue) {
        windowHeight = newValue;
    });
    const windowTimeoutInput = createInput('number', 'Время всплывающего окна в секундах', windowTimeout, function(newValue) {
        windowTimeout = newValue;
    });
    const timeUpdateInput = createInput('number', 'Время всплывающего обновлениясобытий в секундах', timeUpdate, function(newValue) {
        timeUpdate = newValue;
    });

    timerSettingsContainer.appendChild(windowsCountsetingsInput);
    timerSettingsContainer.appendChild(leftPhotoWindowInput);
    timerSettingsContainer.appendChild(topPhotoWindowInput);
	timerSettingsContainer.appendChild(WindowWidthInput);
    timerSettingsContainer.appendChild(WindowHeightInput);
    timerSettingsContainer.appendChild(windowTimeoutInput);
    timerSettingsContainer.appendChild(timeUpdateInput);

    return timerSettingsContainer;
}


function createInput(type, labelText, value, updateCallback) {
    const label = document.createElement('label');
    label.textContent = labelText;

    const input = document.createElement('input');
    input.type = type;
    input.value = value;
    input.style.margin = '5px';
    input.style.display = 'block';
    input.addEventListener('input', function () {
        // Вызываем функцию обратного вызова и передаем ей новое значение
        updateCallback(type === 'number' ? parseInt(this.value) || 0 : this.value);
    });
    
    label.appendChild(input);
    return label;
}
var windowsCountsetings=localStorage.getItem('windowsCountsetings') || 3;
var windowsCount = windowsCountsetings;
var leftPhotoWindow=parseInt(localStorage.getItem('leftPhotoWindow') || 30);
var topPhotoWindow=parseInt(localStorage.getItem('topPhotoWindow') || 100);
var windowTimeout=parseInt(localStorage.getItem('windowTimeout') || 3);
var timeUpdate=parseInt(localStorage.getItem('windowTimeout') || 1);
var windowWidth=parseInt(localStorage.getItem('windowWidth') || 250);
var windowHeight=parseInt(localStorage.getItem('windowHeight') || 400);