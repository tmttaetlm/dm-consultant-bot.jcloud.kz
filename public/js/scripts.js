/* 
 * Main Javascript file
 */
'use strict'

//Create a storage object
var isStorage = {},
    timerID = 0,
    today = getInputDate(),
    thatTime = getInputTime(21600);

function getInputDate(offset){
    offset = offset || 0;
    var date = new Date();
    date.setDate(date.getDate() + offset);
    return date.toISOString().substring(0, 10);
}
function getInputTime(offset){
    offset = offset || 0;
    var date = new Date();
    date.setTime(date.getTime() + offset);
    return date.toTimeString().substring(0, 5);
}

window.onload = function() {
    
    //Catches clicks and send to handler
    document.addEventListener("click", function (event) {
        clickHandler(event.target);
    });
    
    //Catches changes and send to handler
    document.addEventListener("change", function (event) {
        changeHandler(event.target);
    });

    //Catches getting focus
    document.addEventListener("focusin", function (event) {
        mask(event);
    });

    document.addEventListener('focusout', function (event) {
        mask(event);
    });   

    //Отлавливает ввод с клавиатуры и передает в обработчик
    document.body.onkeyup = function(event) {
        mask(event);
    };

    if (document.getElementById('date')) {
        document.getElementById('date').value = today;
        document.getElementById('time').value = thatTime;
    }

}

//Функция отправки Ajax запроса на сервер
function ajax(queryString, callback, params)
{
    var f = callback||function(data){};
    var request = new XMLHttpRequest();
    request.onreadystatechange = function()
    {
            if (request.readyState == 4 && request.status == 200)
            {
                f(request.responseText);
            }
    }
    request.open('POST', queryString);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(params);
}

//Функция отправки Ajax запроса на сервер +JSON
function ajaxJson(queryString, callback, dataObject)
{
    var f = callback||function(data){};
    var data = 'data='+JSON.stringify(dataObject);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function()
    {
            if (request.readyState == 4 && request.status == 200)
            {
                f(JSON.parse(request.responseText));
            }
    }
    
    request.open('POST', queryString);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(data);
}

//Change Handler
function changeHandler(obj)
{    
    if (obj.id == "status")
    {
        ajax('/ticket/changeStatus', function(){
            alert('Статус заявки изменен на "'+obj.selectedOptions[0].innerText+'"');
        }, 'id='+obj.offsetParent.parentNode.dataset.rowId+'&val='+obj.value);
    }
}

//Обработчик кликов на странице
function clickHandler(obj)
{
    if (obj.id == 'addNewTicket') {
        var type = document.getElementById('type').selectedOptions[0].value;
        var hardware = document.getElementById('hardware').selectedOptions[0].value;
        var description = document.getElementById('description').value;
        var customer = document.getElementById('customer').value;
        var datetime = document.getElementById('date').value+' '+document.getElementById('time').value;
        var priority = document.getElementById('priority').selectedOptions[0].value;
        var contact = document.getElementById('contact').value;
        var executor = document.getElementById('executor').value;
        var params = 'type='+type+'&hardware='+hardware+'&description='+description+'&customer='+customer+'&datetime='+datetime+'&priority='+priority+'&contact='+contact+'&executor='+executor;
        ajax('/ticket/addNewTicket', function(){
			document.location='/';
        }, params);
    }

}

function mask(event) {
    if (event.target.type == 'tel') {
        var pos = event.target.selectionStart;
        if (pos < 3) event.preventDefault();
        var matrix = "+7 (___) ___ ____",
            i = 0,
            def = matrix.replace(/\D/g, ""),
            val = event.target.value.replace(/\D/g, ""),
            new_value = matrix.replace(/[_\d]/g, function(a) {
                return i < val.length ? val.charAt(i++) || def.charAt(i) : a
            });
        i = new_value.indexOf("_");
        if (i != -1) {
            i < 5 && (i = 3);
            new_value = new_value.slice(0, i)
        }
        var reg = matrix.substr(0, event.target.value.length).replace(/_+/g,
            function(a) {
                return "\\d{1," + a.length + "}"
            }).replace(/[+()]/g, "\\$&");
        reg = new RegExp("^" + reg + "$");
        if (!reg.test(event.target.value) || event.target.value.length < 5 || event.keyCode > 47 && event.keyCode < 58) {
            event.target.value = new_value;
        };
        if (event.type == "focusout" && event.target.value.length < 5)  {
            event.target.value = "";
        }
    }
}
