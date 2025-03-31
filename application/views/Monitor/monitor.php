<script>
document.addEventListener("DOMContentLoaded", function () {
    var maxRows = 20;
    var eventNumber = 0;
    var initialData = [];
    var colorCarousel = ["#4CAF50", "#FFD700", "#FF6347"]; // Зеленый, желтый, красный
    var useMqtt = false;

    // Модель данных
    function RowModel(dateTime, event, source, information, color) {
        this.dateTime = dateTime;
        this.event = event;
        this.source = source;
        this.information = information;
        this.color = color;
    }

    // Функция для отображения данных в таблице
    function renderTable(data) {
        var tbody = document.querySelector("#myTable tbody");
        var tbody = document.querySelector("#myTable tbody");

        // Очищаем предыдущие данные
        tbody.innerHTML = "";

        // Создаем строки таблицы
        data.forEach(function (row) {
            var tr = document.createElement("tr");

            // Создаем ячейки и заполняем данными, кроме свойства color
            for (var key in row) {
                if (key !== 'color') {
                    var td = document.createElement("td");
                    td.textContent = row[key];
                    tr.appendChild(td);
                }
            }

            // Определяем цвет строки
            tr.style.backgroundColor = row.color;

            tbody.appendChild(tr);
        });
    }

    function updateLastEventId(){
        if(eventNumber == 0)
        {
            fetch("http://localhost/parkresident/index.php/Welcome/getidevent")
            .then(response => response.json())
            .then(data => {
                eventNumber = parseInt(data.GEN_ID);
                fetchDataAndRenderTable();
            })
            .catch(error => console.error("Ошибка при выполнении GET-запроса:", error));
        }else{
            fetchDataAndRenderTable();
        }
    }

    // Функция для выполнения GET-запроса с параметром event_number и обновления таблицы
    function fetchDataAndRenderTable() {
        fetch("http://localhost/parkresident/index.php/Welcome/geteventfrom/${eventNumber}")
            .then(response => response.json())
            .then(data => {
                // Обработка полученных данных
                var localEventNumber = parseInt(data.id);

                if (isNaN(localEventNumber)) 
                    return;

                if(eventNumber == localEventNumber)
                    return;


                eventNumber = localEventNumber
                var dateTime = data.timestamp + ' ' + data.id;
                var eventname = data.eventname
                var eventplace = data.eventplace
                var eventinfo = data.eventinfo
               
                //var evencolor = '#' + ('000000' + parseInt(data.evencolor).toString(16)).slice(-6);
                var evencolor = '#' + parseInt(data.evencolor).toString(16).toUpperCase().padStart(6, '0');

                // Преобразуем полученные данные в модель данных
                var newData = new RowModel(
                    dateTime,
                    eventname,
                    eventplace,
                    eventinfo,
                    evencolor
                );

                // Добавляем новые данные в начало массива
                initialData.unshift(newData);

                // Обрезаем массив до максимального количества записей
                initialData = initialData.slice(0, maxRows);

                // Обновляем таблицу
                renderTable(initialData);

                //+1 к событию
                //eventNumber++;
            })
            .catch(error => console.error("Ошибка при выполнении GET-запроса:", error));
    }

    // Вспомогательная функция для получения следующего цвета из карусели
    function getNextColor() {
        var colorIndex = initialData.length % colorCarousel.length;
        return colorCarousel[colorIndex];
    }

    function connect() {
        var hostname = "194.87.237.67";
        var port = "8090";
        var clientId = "js-utility-" + makeid();
        var path = "/ws";

        client = new Paho.MQTT.Client(hostname, Number(port), path, clientId);
        
        // set callback handlers
        client.onConnectionLost = function (responseObject) {
            console.log("Connection Lost: "+responseObject.errorMessage);
        }

        client.onMessageArrived = function (message) {
            console.log("Message Arrived: " + message.payloadString);
            console.log("Topic:     " + message.destinationName);
            console.log("QoS:       " + message.qos);
            console.log("Retained:  " + message.retained);
            // Read Only, set if message might be a duplicate sent from broker
            console.log("Duplicate: " + message.duplicate);

            // Получено сообщение с брокера, вызываем функцию для обработки данных
            updateLastEventId();
        }

        // Called when the connection is made
        function onConnect(){
            console.log("Connected!");
            client.subscribe("MB");
        }
        
        client.connect({
            onSuccess: onConnect, 
            mqttVersion: 3
        });
    }

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      
        for (var i = 0; i < 5; i++)
          text += possible.charAt(Math.floor(Math.random() * possible.length));
      
        return text;
    }

    if(useMqtt){
        connect();
    }else{
        setInterval(updateLastEventId, 1000);
    }
});
</script>  
  <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
            position: relative;
            transition: background-color 0.3s; /* Анимация перехода цвета */
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5; /* Цвет подсветки при наведении */
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: max-content;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        td:hover .tooltip .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>


<table id="myTable">
    <thead>
        <tr>
            <th>Дата/Время</th>
            <th>Событие</th>
            <th>Источник</th>
            <th>Информация</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

