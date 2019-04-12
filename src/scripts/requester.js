function acquireAndStoreAusbildungsnachweis(hideBar) {
    let textareas = document.getElementsByClassName("nachweisEintrag");
    let arbeitszeiten = document.getElementsByClassName("arbeitszeit");
    let innerContents = [];
    let arbeitszeitenContent = [];


    for (let i = 0; i < textareas.length; i++) {
        innerContents.push(replaceSemicoloms(textareas[i].value));
        arbeitszeitenContent.push(replaceSemicoloms(arbeitszeiten[i].value));
    }

    let implode = innerContents.join(";");
    let implodedArbeitszeiten = arbeitszeitenContent.join(";");
    let id = document.getElementsByClassName("nachweisID")[0].innerText;
    let payload = "ID=" + id + "&Inhalt=" + implode + "&Arbeitszeiten=" + implodedArbeitszeiten;

    if (hideBar) {
        sendSilentXHR("src/actions/insert.php", payload);
    } else {
        sendXHR("src/actions/insert.php", payload, "informationBarContent");
    }


}

function publish() {
    let check = confirm("Möchtest du diesen Eintrag wirklich freigeben?");
    if (check === true) {
        let id = document.getElementById('nachweisID').innerText;
        let payload = 'action=publish&id=' + id;
        let newState = [];
        newState['Freigabestatus'] = 1;
        let queueView = document.getElementById("isQueueView");
        if (queueView != null) {
            sendXHRWithReload("src/actions/modifyState.php", payload, "informationBarContent", newState);
        }
        sendXHRWithButtonSwitch("src/actions/modifyState.php", payload, "informationBarContent", newState);

    }
}

function faulty() {
    let check = confirm("Möchtest du diesen Eintrag wirklich zur Korrektur geben?");
    if (check === true) {
        let textareas = document.getElementsByClassName("nachweisKorrekturvermerk");
        let innerContents = [];
        for (let i = 0; i < textareas.length; i++) {
            innerContents.push(replaceSemicoloms(textareas[i].value));
        }
        let implode = innerContents.join(";");
        let id = document.getElementById('nachweisID').innerText;
        let AVID = document.getElementById("AVID").innerText;
        let newState = [];
        newState['Freigabestatus'] = 2;
        let payload = "id=" + id + "&AVID=" + AVID + "&Korrekturvermerk=" + implode + "&action=setFaulty"
        let queueView = document.getElementById("isQueueView");
        if (queueView !== null) {
            sendXHRWithReload("src/actions/modifyState.php", payload, "informationBarContent", newState);
        } else {
            sendXHRWithAusbilderButtonSwitch("src/actions/modifyState.php", payload, "informationBarContent", newState);
        }

    }
}

function sign() {
    let check = confirm("Möchtest du diesen Eintrag wirklich signieren? Dieser Schritt kann nicht rückgängig gemacht werden.");
    if (check === true) {
        let id = document.getElementById('nachweisID').innerText;
        let AVID = document.getElementById("AVID").innerText;
        let newState = [];
        newState['Freigabestatus'] = 3;
        let payload = "id=" + id + "&AVID=" + AVID + "&action=sign";
        sendXHRWithReload("src/actions/modifyState.php", payload, "informationBarContent", newState);
    }
}

function unpublish() {
    let check = confirm("Möchtest du diesen Eintrag wirklich zurückziehen?");
    if (check === true) {
        let id = document.getElementById('nachweisID').innerText;
        let payload = 'action=unpublish&id=' + id;
        let newState = [];
        newState['Freigabestatus'] = 0;
        sendXHRWithButtonSwitch("src/actions/modifyState.php", payload, "informationBarContent", newState);
    }
}


function loadAusbildungsnachweis(iterator) {

    let ID = Number(document.getElementById("nachweisID").innerText);
    ID = ID + iterator;

    if (ID <= 0) {
        return;
    }

    getAusbildugnsnachweisByID("src/actions/getData.php", ID, iterator);
}

function loadAusbildungsnachweisWithKey(iterator, key) {
    let ID = Number(document.getElementById("nachweisID").innerText);
    ID = ID + iterator;

    if (ID <= 0) {
        return;
    }

    getAusbildugnsnachweisByIDAndKey("src/actions/getData.php", ID, key, iterator);
}

function loadAusbildungsnachweisAsAusbilder(iterator) {
    let ID = Number(document.getElementById("nachweisID").innerText);
    ID = ID + iterator;
    let AVID = document.getElementById("AVID").innerText;

    if (ID <= 0) {
        return;
    }

    getAusbildugnsnachweisByIDAndAVID("src/actions/getData.php", ID, AVID, iterator);
}

function replaceContents(json, isAusbilder, isExternal) {
    if (json['Inhalt'] != null) {
        let inhalt = json['Inhalt'].split(";");
        for (let i = 0; i < inhalt.length; i++) {
            replaceValueByID(i + 1, inhalt[i]);
        }

    } else {
        for (let i = 1; i <= 7; i++) {
            replaceValueByID(i, "");
        }
    }

    if (json['Arbeitszeiten'] != null) {

        let arbeitszeiten = json['Arbeitszeiten'].split(";");
        for (let i = 0; i < arbeitszeiten.length; i++) {
            replaceValueByID("arbeitszeit-" + i, arbeitszeiten[i]);
        }

    } else {
        for (let i = 0; i < 7; i++) {
            replaceValueByID("arbeitszeit-" + i, "00:00");
        }
    }

    if (!isExternal) {

        if (json['Korrekturvermerk'] != null) {
            showClass("nachweisKorrekturvermerk");
            let korrekturvermerke = json['Korrekturvermerk'].split(";");
            for (let i = 0; i < korrekturvermerke.length; i++) {
                replaceValueByID("korrekturvermerk-" + (i + 1), korrekturvermerke[i]);
            }

        } else {
            if (!isAusbilder) {
                hideByClass("nachweisKorrekturvermerk");
            }
            for (let i = 0; i < 7; i++) {
                replaceValueByID("korrekturvermerk-" + (i + 1), "");
            }

        }
    }
}

function loadAusbildungsnachweisCallbackForExternal(data) {
    if (data != null) {
        let json = JSON.parse(data);
        switchExternal(json);
        replaceContents(json, false, true);
        replaceContentByID("nachweisID", json['ID']);
    } else {
        // hide next button
    }

}


function loadAusbildungsnachweisCallback(data) {
    if (data != null) {
        let json = JSON.parse(data);
        switchButtons(json);
        replaceContents(json, false);
        replaceContentByID("nachweisID", json['ID']);
    } else {
        // hide next button
    }

}

function loadAusbildungsnachweisAsAusbilderCallback(data) {
    if (data != null) {
        console.log(data);
        let json = JSON.parse(data);
        switchAusbilderButtons(json);
        replaceContents(json, true);
        replaceContentByID("nachweisID", json['ID']);
    } else {
        // hide next button
    }

}


function replaceSemicoloms(str) {
    return str.replace(/;/, '');
}


function getAusbildugnsnachweisByID(url, ID, iterator) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200) {

                loadAusbildungsnachweisCallback(xhr.response);
                replaceDates(iterator);
            }
            if (xhr.status == 404) {

            }

        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send("action=getAusbildungsnachweis&ID=" + ID);
}

function getAusbildugnsnachweisByIDAndKey(url, ID, key, iterator) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200) {
                loadAusbildungsnachweisCallbackForExternal(xhr.response);
                replaceDates(iterator);
            }
            if (xhr.status == 404) {

            }

        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send("action=getAusbildungsnachweisWithKey&ID=" + ID + "&key=" + key);
}

function getAusbildugnsnachweisByIDAndAVID(url, ID, AVID, iterator) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState === 4) {
            console.log(xhr);
            if (xhr.status == 200) {
                loadAusbildungsnachweisAsAusbilderCallback(xhr.response);
                replaceDates(iterator);
            }
            if (xhr.status == 404) {

            }

        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send("action=getAusbildungsnachweisAsAusbilder&ID=" + ID + "&AVID=" + AVID);
}


function sendXHR(url, payload, ID) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log(xhr);
            if (xhr.status == 200) {
                let message = "Die Daten wurden gespeichert";
                toggleInformationBar(ID, message, "success");
            } else {
                let message = "Fehler beim Speichern der Daten";
                toggleInformationBar(ID, message, "error");
            }
        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(payload);
}

function sendSilentXHR(url, payload) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(payload);
}

function sendXHRWithButtonSwitch(url, payload, ID, newState) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200) {
                let message = "Die Daten wurden gespeichert";
                toggleInformationBar(ID, message, "success");
                switchButtons(newState);
            } else {
                let message = "Fehler beim Speichern der Daten";
                toggleInformationBar(ID, message, "error");
            }
        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(payload);
}

function sendXHRWithAusbilderButtonSwitch(url, payload, ID, newState) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log(xhr);
            if (xhr.status == 200) {
                let message = "Die Daten wurden gespeichert";
                toggleInformationBar(ID, message, "success");
                switchAusbilderButtons(newState);
            } else {
                let message = "Fehler beim Speichern der Daten";
                toggleInformationBar(ID, message, "error");
            }
        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(payload);
}

function sendXHRWithReload(url, payload, ID, newState) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log(xhr);
            if (xhr.status == 200) {
                let message = "Die Daten wurden gespeichert";
                location.reload();
            } else {
                let message = "Fehler beim Speichern der Daten";
                toggleInformationBar(ID, message, "error");
            }
        }
    };

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(payload);
}