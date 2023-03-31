const getIdConversation = () => {
    const splits = window.location.href.split('/');
    const lastSegment = splits[splits.length - 1];
    const conversationId = parseInt(lastSegment, 10);
    return isNaN(conversationId) ? null : conversationId;
}
const buttonUserEl = document.querySelector('#form-chat-user #submit')
buttonUserEl?.addEventListener('click', () => {
    const textValue = document.getElementById('input-message').value
    if (textValue.length > 0) {
        sendMessageUser(textValue)
    }
})
const sendMessageUser = async (message) => {
    let request;
    request = await fetch('https://localhost/chat/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            message
        })
    });
}


const buttonAdminEl = document.querySelector('#form-chat-admin #submit')

buttonAdminEl?.addEventListener('click', () => {
    const textValue = document.getElementById('input-message').value
    if (textValue.length > 0) {
        sendMessageAdmin(textValue)
    }
})
const sendMessageAdmin = async (message) => {
    let request;
    const conversationId = getIdConversation()
    request = await fetch(`https://localhost/admin/conversation/${conversationId}/answer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            message
        })
    });
}

const messageContainerEl = document.querySelector(".message-container")
const conversationId = getIdConversation()
console.log(conversationId)
if (conversationId === null) {
    listenConversation()
} else if (typeof conversationId === "number") {
    console.log("okk condit")
    listenConversation(conversationId)
}

function listenConversation(val = null) {
    const id = val !== null ? '/' + val : ''
    let myTemplate = document.querySelector("#template-message")
    setInterval(function () {
        fetch('/conversation/listen' + id)
            .then(response => response.json())
            .then(listData => {
                messageContainerEl.innerHTML = ""
                for (const data of listData) {
                    const articleElement = myTemplate.content.cloneNode(true)
                    articleElement.querySelector("#user-info").innerHTML = data.user.lastname + " " + data.user.firstname + " - " + new Date(data.timestamp.date).toLocaleString()
                    articleElement.querySelector("#user-text").innerHTML = data.message
                    articleElement.querySelector("article").classList.add(data.user.role === "ROLE_USER" ? "user-message" : "admin-message")
                    messageContainerEl.append(articleElement)
                }
            })
            .catch(error => console.error(error));
    }, 2500);
}

