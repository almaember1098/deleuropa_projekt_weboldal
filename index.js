// fetch and render the navbar with vue
fetch(urlRoot + "menus.json")
    .then(response => {
        return response.json()
    })
    .then(linksFromJson => {
        new Vue({
            el: "#navbar",
            data: {
                links: linksFromJson,
                rootUrl: urlRoot
            }
        })
    })

// get the name of the current page and show it
const pagePath = window.location.href
const pageNameArray = pagePath.split("?")
var pageName = pageNameArray[pageNameArray.length - 1]

// if pageName is empty, we will load the default page
if(pageName == "" || pageNameArray.length == 1) {
    pageName = "default"
}

// this will be sent to service.php
var apiRequest = {
    action: "getPage",
    params: {
        page: pageName
    }
}

var apiRequestJson = JSON.stringify(apiRequest)

// send the request
fetch(urlRoot + "service.php", {
    method: "POST",
    headers: {
        "Content-Type":"application/json"
    },
    body: apiRequestJson
})
    .then(response => response.json())
    .then(data => {
        var pageTitle, pageContent
        if(data.errno != 0) {
            pageTitle = "Hiba :("
            pageContent = "Sajnos hiba történt. A szervertől ezt az információt kaptuk vissza: "
            pageContent += data.description
        } else {
            pageTitle = data.pageTitle
            pageContent = data.pageContent
        }
        
        new Vue({
            el: "#content",
            data: {
                page: {
                    title: pageTitle,
                    content: pageContent
                }
            }
        })
        document.getElementById("loader").remove()
        document.getElementById("content").classList.remove("nodisplay")
    })