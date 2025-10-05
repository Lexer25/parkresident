var userLogin = getCookie("userLogin") || "";
var maxRows = parseInt(getCookie("maxRows")) || 20;
var showPhoto = getCookie("showPhoto") === "true" || true;
var maxWindows = parseInt(getCookie("maxWindows")) || 3;
var windowTimeout = parseInt(getCookie("windowTimeout")) || 3;
var windowSize = parseInt(getCookie("windowSize")) || 100;
var leftPhotoWindow = parseInt(getCookie("leftPhotoWindow")) || 30;
var topPhotoWindow = parseInt(getCookie("topPhotoWindow")) || 100;
var useMqtt = getCookie("useMqtt") === "true" || false;
var baseUrl = getCookie("baseUrl") || "http://26.237.145.169/totcon";
var getIdEventUrl = getCookie("getIdEventUrl") || "/index.php/Welcome/getidevent";
var getEventFromUrl = getCookie("getEventFromUrl") || "/index.php/Welcome/geteventfrom";
var getContactInfoUrl = getCookie("getContactInfoUrl") || "/index.php/Welcome/getContactInfo";

var myButton = document.getElementById('buttonSettings');

myButton.addEventListener('click', function() {
    createSettingsModal();
});

// Функция для сохранения значений в localStorage
function saveSettings() {
    localStorage.setItem('userLogin', userLogin);
    localStorage.setItem('maxRows', maxRows);
    localStorage.setItem('showPhoto', showPhoto);
    localStorage.setItem('maxWindows', maxWindows);
    localStorage.setItem('windowTimeout', windowTimeout);
    localStorage.setItem('windowSize', windowSize);
    localStorage.setItem('leftPhotoWindow', leftPhotoWindow);
    localStorage.setItem('topPhotoWindow', topPhotoWindow);
    localStorage.setItem('useMqtt', useMqtt);
    localStorage.setItem('baseUrl', baseUrl);
    localStorage.setItem('getIdEventUrl', getIdEventUrl);
    localStorage.setItem('getEventFromUrl', getEventFromUrl);
    localStorage.setItem('getContactInfoUrl', getContactInfoUrl);
}

// Функция для загрузки значений из localStorage
function loadSettings() {
    userLogin = localStorage.getItem('userLogin') || userLogin;
    maxRows = localStorage.getItem('maxRows') || maxRows;
    showPhoto = localStorage.getItem('showPhoto') || showPhoto;
    maxWindows = localStorage.getItem('maxWindows') || maxWindows;
    windowTimeout = localStorage.getItem('windowTimeout') || windowTimeout;
    windowSize = localStorage.getItem('windowSize') || windowSize;
    leftPhotoWindow = localStorage.getItem('leftPhotoWindow') || leftPhotoWindow;
    topPhotoWindow = localStorage.getItem('topPhotoWindow') || topPhotoWindow;
    useMqtt = localStorage.getItem('useMqtt') === 'true'; // преобразуем в boolean
    baseUrl = localStorage.getItem('baseUrl') || baseUrl;
    getIdEventUrl = localStorage.getItem('getIdEventUrl') || getIdEventUrl;
    getEventFromUrl = localStorage.getItem('getEventFromUrl') || getEventFromUrl;
    getContactInfoUrl = localStorage.getItem('getContactInfoUrl') || getContactInfoUrl;
}


function createSettingsModal() {
    loadSettings();
    
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

    const saveButton = document.createElement('button');
    saveButton.textContent = 'Сохранить';
    saveButton.style.margin = '5px';
    saveButton.addEventListener('click', () => {
        saveSettings();
    });

    const showPhotoCheckbox = document.createElement('input');
    showPhotoCheckbox.type = 'checkbox';
    showPhotoCheckbox.checked = showPhoto; // установим начальное значение переменной
    showPhotoCheckbox.addEventListener('change', function () {
        showPhoto = this.checked; // обновим переменную при изменении чекбокса
    });

    const maxWindowsInput = document.createElement('input');
    maxWindowsInput.type = 'number';
    maxWindowsInput.value = maxWindows; // установим начальное значение переменной
    maxWindowsInput.addEventListener('input', function () {
        maxWindows = parseInt(this.value) || 0; // обновим переменную при вводе числа
    });

    const closeModal = () => {
        setCookie("userLogin", userLogin);
        setCookie("maxRows", maxRows.toString());
        setCookie("showPhoto", showPhoto.toString());
        setCookie("maxWindows", maxWindows.toString());
        setCookie("windowTimeout", windowTimeout.toString());
        setCookie("windowSize", windowSize.toString());
        setCookie("leftPhotoWindow", leftPhotoWindow.toString());
        setCookie("topPhotoWindow", topPhotoWindow.toString());
        setCookie("useMqtt", useMqtt.toString());
        setCookie("baseUrl", baseUrl);
        setCookie("getIdEventUrl", getIdEventUrl);
        setCookie("getEventFromUrl", getEventFromUrl);
        setCookie("getContactInfoUrl", getContactInfoUrl);

        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
    };

    closeButton.addEventListener('click', closeModal);

    header.appendChild(titleBar);
    header.appendChild(closeButton);
    modal.appendChild(header);

    // Добавим элементы управления в модальное окно
    const settingsContainer = document.createElement('div');
    settingsContainer.className = 'settings-container';

     // Добавляем элементы управления для новых переменных
     const userLoginInput = createInput('text', 'User Login', userLogin, function(newValue) {
        userLogin = newValue;
    });
    
    const maxRowsInput = createInput('number', 'Max Rows', maxRows, function(newValue) {
        maxRows = newValue;
    });
    
    const windowTimeoutInput = createInput('number', 'Window Timeout', windowTimeout, function(newValue) {
        windowTimeout = newValue;
    });
    
    const windowSizeInput = createInput('number', 'Window Size', windowSize, function(newValue) {
        windowSize = newValue;
    });
    
    const leftPhotoWindowInput = createInput('number', 'Left Photo Window', leftPhotoWindow, function(newValue) {
        leftPhotoWindow = newValue;
    });
    
    const topPhotoWindowInput = createInput('number', 'Top Photo Window', topPhotoWindow, function(newValue) {
        topPhotoWindow = newValue;
    });
    
    const useMqttCheckbox = createCheckbox('Use MQTT', useMqtt, function(newValue) {
        useMqtt = newValue;
    });
    
    const baseUrlInput = createInput('text', 'Base URL', baseUrl, function(newValue) {
        baseUrl = newValue;
    });
    
    const getIdEventUrlInput = createInput('text', 'Get ID Event URL', getIdEventUrl, function(newValue) {
        getIdEventUrl = newValue;
    });
    
    const getEventFromUrlInput = createInput('text', 'Get Event From URL', getEventFromUrl, function(newValue) {
        getEventFromUrl = newValue;
    });
    
    const getContactInfoUrlInput = createInput('text', 'Get Contact Info URL', getContactInfoUrl, function(newValue) {
        getContactInfoUrl = newValue;
    });
    
 
     settingsContainer.appendChild(userLoginInput);
     settingsContainer.appendChild(maxRowsInput);
     settingsContainer.appendChild(windowTimeoutInput);
     settingsContainer.appendChild(windowSizeInput);
     settingsContainer.appendChild(leftPhotoWindowInput);
     settingsContainer.appendChild(topPhotoWindowInput);
     settingsContainer.appendChild(useMqttCheckbox);
     settingsContainer.appendChild(baseUrlInput);
     settingsContainer.appendChild(getIdEventUrlInput);
     settingsContainer.appendChild(getEventFromUrlInput);
     settingsContainer.appendChild(getContactInfoUrlInput);
     settingsContainer.appendChild(saveButton);

    modal.appendChild(settingsContainer);

    document.body.appendChild(modal);

    makeModalDraggable(modal);

    modal.style.height = 'auto';
    modal.style.display = 'block';
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

function createCheckbox(labelText, isChecked, updateCallback) {
    const label = document.createElement('label');
    label.textContent = labelText;

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = isChecked;
    checkbox.style.margin = '5px';
    checkbox.style.display = 'block';
    checkbox.addEventListener('change', function () {
        // Вызываем функцию обратного вызова и передаем ей новое значение
        updateCallback(this.checked);
    });

    label.appendChild(checkbox);
    return label;
}


// Функция для установки куки
function setCookie(name, value) {
    document.cookie = `${name}=${value}; path=/`;
}

// Функция для получения значения куки
function getCookie(name) {
    const cookies = document.cookie.split(';').map(cookie => cookie.trim());
    const desiredCookie = cookies.find(cookie => cookie.startsWith(`${name}=`));
    return desiredCookie ? desiredCookie.split('=')[1] : null;
}
