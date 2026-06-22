<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>المساعد الذكي</title>

<style>

body{
font-family:Tahoma;
background:#f4f6f9;
padding:30px;
}

.chat{
max-width:900px;
margin:auto;
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 0 15px rgba(0,0,0,.1);
}

.input-row{
display:flex;
gap:10px;
}

input{
flex:1;
padding:12px;
}

button{
padding:12px 20px;
background:#1e40af;
color:white;
border:none;
cursor:pointer;
}

.message{
background:#e0f2fe;
padding:12px;
margin-top:10px;
border-radius:10px;
}

</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

<div class="chat">

<h2>🤖 المساعد الذكي للاستشارات</h2>

<div class="input-row">

<input
id="message"
placeholder="اكتب سؤالك هنا"
>

<button onclick="sendMessage()">
إرسال
</button>

</div>

<div id="response"></div>

</div>

<script>

async function sendMessage()
{
let message =
document.getElementById('message').value;

if(message.trim() === '')
return;

try{

let response =
await fetch('/ai-chat/send',{

method:'POST',

headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':
document.querySelector('meta[name="csrf-token"]').content
},

body:JSON.stringify({
message:message
})

});

let data =
await response.json();

document.getElementById('response').innerHTML +=
`
<div class="message">

<b>أنت:</b>
${message}

<br><br>

<b>المساعد:</b>
${data.reply}

</div>
`;

document.getElementById('message').value='';

}
catch(error){

document.getElementById('response').innerHTML +=
`
<div class="message">
حدث خطأ في الاتصال بالخادم
</div>
`;

}

}

</script>

</body>
</html>